<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Musician;
use App\Form\MusicianType;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/musicians')]
class MusicianController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'musician_show', methods: ['GET'])]
    public function show(Musician $musician, string $slug): Response
    {
        $expectedSlug = $musician->getSlug();

        if ($slug != $expectedSlug) {
            return $this->redirectToRoute('musician_show', [
                'id' => $musician->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        return $this->render('musician/show.html.twig', [
            'musician' => $musician,
        ]);
    }

    #[Route('/{id}/edit', name: 'musician_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musician $musician, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(MusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Musician updated successfully.');
            return $this->redirectToRoute('musician_show', ['id' => $musician->getId()]);
        }

        return $this->render('musician/edit.html.twig', [
            'musician' => $musician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'musician_delete', methods: ['POST'])]
    public function delete(Request $request, Musician $musician, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $musician->getId(), $request->request->get('_token'))) {
            $em->remove($musician);
            $em->flush();
            $this->addFlash('success', 'Musician deleted.');
        }

        return $this->redirectToRoute('homepage');
    }

    #[Route('/', name: 'musician_list')]
    public function list(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, BandRepository $bandRepository): Response
    {
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');
        $type = $request->query->get('type');
        $bandId = $request->query->get('band_id');

        $allowedSorts = ['name', 'created'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $qb = $em->getRepository(Musician::class)->createQueryBuilder('m');

        $band = null;

        if ($type === 'solo') {
            $qb->leftJoin('m.bands', 'b')
            ->andWhere('b.id IS NULL');
        } elseif ($type === 'band') {
            $qb->join('m.bands', 'b');

            if ($bandId) {
                $band = $em->getRepository(Band::class)->findOneBy(['id' => $bandId]);

                $qb->andWhere('b.id = :bandId')
                ->setParameter('bandId', $bandId);
            }
        } elseif ($bandId) {
            // If type is not specified but band is
            $qb->join('m.bands', 'b')
            ->andWhere('b.id = :bandId')
            ->setParameter('bandId', $bandId);
        }

        // Sorting
        if ($sort === 'created') {
            $qb->orderBy('m.createdAt', $order);
        } else {
            $qb->orderBy('m.name', $order);
        }

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            15,
            [
                'sortFieldWhitelist' => [],
                'defaultSortFieldName' => null,
                'defaultSortDirection' => null,
                'sortFieldParameterName' => 'ignore',
                'sortDirectionParameterName' => 'ignore',
            ]
        );

        return $this->render('musician/list.html.twig', [
            'band' => $band,
            'pagination' => $pagination,
            'bands' => $bandRepository->findAll(),
            'selectedBandId' => $bandId,
            'selectedType' => $type,
        ]);
    }
}

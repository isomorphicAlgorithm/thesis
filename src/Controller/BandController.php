<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bands')]
class BandController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'band_show', methods: ['GET'])]
    public function show(Band $band, string $slug): Response
    {
        // Get the band’s slug stored in the database
        $expectedSlug = $band->getSlug();

        // If the slug in the URL doesn’t match the stored slug, redirect to the correct URL
        if ($slug != $expectedSlug) {
            return $this->redirectToRoute('band_show', [
                'id' => $band->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        return $this->render('band/show.html.twig', [
            'band' => $band,
        ]);
    }

    #[Route('/{id}/edit', name: 'band_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Band $band, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Band updated successfully.');
            return $this->redirectToRoute('band_show', ['id' => $band->getId()]);
        }

        return $this->render('band/edit.html.twig', [
            'band' => $band,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'band_delete', methods: ['POST'])]
    public function delete(Request $request, Band $band, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $band->getId(), $request->request->get('_token'))) {
            $em->remove($band);
            $em->flush();
            $this->addFlash('success', 'Band deleted.');
        }

        return $this->redirectToRoute('homepage');
    }

    #[Route('/', name: 'band_list')]
    public function list(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');

        // Validate sort and order values
        $allowedSorts = ['name', 'created'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $qb = $em->getRepository(Band::class)->createQueryBuilder('b');

        switch ($sort) {
            case 'created':
                $qb->orderBy('b.createdAt', $order);
                break;
            case 'name':
            default:
                $qb->orderBy('b.name', $order);
                break;
        }

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            12,
            [
                'sortFieldWhitelist' => [],
                'defaultSortFieldName' => null,
                'defaultSortDirection' => null,
                'sortFieldParameterName' => 'ignore', // prevent KNP from parsing ?sort=
                'sortDirectionParameterName' => 'ignore',
            ]
        );

        return $this->render('band/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}

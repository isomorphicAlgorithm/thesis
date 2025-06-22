<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Genre;
use App\Entity\Musician;
use App\Form\MusicianType;
use App\Repository\BandRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

    #[Route('/', name: 'musician_list')]
    public function list(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, BandRepository $bandRepository, GenreRepository $genreRepository): Response
    {
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');
        $type = $request->query->get('type');
        $bandId = $request->query->get('band_id');
        $genreId = $request->query->get('genre_id');

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

        $genre = null;

        if ($genreId) {
            $genre = $em->getRepository(Genre::class)->find($genreId);
            $qb->join('m.genres', 'g')
                ->andWhere('g.id = :genreId')
                ->setParameter('genreId', $genreId);
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
            'genre' => $genre,
            'genres' => $genreRepository->findAll(),
            'selectedBandId' => $bandId,
            'selectedType' => $type,
            'selectedGenreId' => $genreId,
            'totalMusicians' =>  $pagination->getTotalItemCount(),
        ]);
    }

    #[Route('/create', name: 'musician_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $musician = new Musician();

        $form = $this->createForm(MusicianType::class, $musician);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $coverFile */
            $coverFile = $form->get('coverImageFile')->getData();

            if ($coverFile) {
                $newFilename = uniqid('musician_', true) . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('musicians_covers_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Cover could not be uploaded');
                }

                $musician->setCoverImage($newFilename);
            }

            $em->persist($musician);
            $em->flush();

            $this->addFlash('success', 'Musician created successfully');

            return $this->redirectToRoute('musician_show', [
                'id'   => $musician->getId(),
                'slug' => $musician->getSlug(),
            ]);
        }

        return $this->render('musician/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}-{slug}/edit', name: 'musician_edit', methods: ['GET', 'POST'])]
    public function edit(Musician $musician, Request $request, EntityManagerInterface $em): Response
    {
        // Links handling
        $rawLinks = $musician->getLinks();

        if (is_array($rawLinks)) {
            $musician->setLinks(array_values($rawLinks));
        }

        $form = $this->createForm(MusicianType::class, $musician);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $coverFile */
            $coverFile = $form->get('coverImageFile')->getData();

            if ($coverFile) {
                $newFilename = uniqid('musician_', true) . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('musicians_covers_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Cover could not be uploaded');
                }

                $musician->setCoverImage($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Musician updated successfully');

            return $this->redirectToRoute('musician_show', [
                'id'   => $musician->getId(),
                'slug' => $musician->getSlug(),
            ]);
        }

        return $this->render('musician/edit.html.twig', [
            'form' => $form->createView(),
            'musician' => $musician,
        ]);
    }

    #[Route('/{id}-{slug}/delete', name: 'musician_delete', methods: ['POST'])]
    public function delete(Musician $musician, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $musician->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($musician);
        $em->flush();

        $this->addFlash('success', 'Musician deleted');

        return $this->redirectToRoute('musician_list');
    }
}

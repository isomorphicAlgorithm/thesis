<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\Genre;
use App\Form\BandType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/', name: 'band_list')]
    public function list(Request $request, GenreRepository $genreRepository, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');
        $genreId = $request->query->get('genre_id');

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

        $genre = null;

        if ($genreId) {
            $genre = $em->getRepository(Genre::class)->find($genreId);
            $qb->join('b.genres', 'g')
                ->andWhere('g.id = :genreId')
                ->setParameter('genreId', $genreId);
        }

        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            12,
            [
                'sortFieldWhitelist' => [],
                'defaultSortFieldName' => null,
                'defaultSortDirection' => null,
                'sortFieldParameterName' => 'ignore',
                'sortDirectionParameterName' => 'ignore',
            ]
        );

        return $this->render('band/list.html.twig', [
            'pagination' => $pagination,
            'genre' => $genre,
            'genres' => $genreRepository->findAll(),
            'selectedGenreId' => $genreId,
            'totalBands' =>  $pagination->getTotalItemCount(),
        ]);
    }

    #[Route('/create', name: 'band_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $band = new Band();

        $form = $this->createForm(BandType::class, $band);

        $form->handleRequest($request);

        // Debugging
        /*
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    dump($error->getMessage());
                }
                dd('Form invalid');
            }
        }*/

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $coverFile */
            $coverFile = $form->get('coverImageFile')->getData();

            if ($coverFile) {
                $newFilename = uniqid('band_', true) . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('bands_covers_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Cover could not be uploaded');
                }

                $band->setCoverImage($newFilename);
            }

            $em->persist($band);
            $em->flush();

            $this->addFlash('success', 'Band created successfully');

            return $this->redirectToRoute('band_show', [
                'id'   => $band->getId(),
                'slug' => $band->getSlug(),
            ]);
        }

        return $this->render('band/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}-{slug}/edit', name: 'band_edit', methods: ['GET', 'POST'])]
    public function edit(Band $band, Request $request, EntityManagerInterface $em): Response
    {
        // Links handling
        $rawLinks = $band->getLinks();

        if (is_array($rawLinks)) {
            $band->setLinks(array_values($rawLinks));
        }

        $form = $this->createForm(BandType::class, $band);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $coverFile */
            $coverFile = $form->get('coverImageFile')->getData();

            if ($coverFile) {
                $newFilename = uniqid('band_', true) . '.' . $coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('bands_covers_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Cover could not be uploaded');
                }

                $band->setCoverImage($newFilename);
            }

            $em->flush();

            $this->addFlash('success', 'Band updated successfully');

            return $this->redirectToRoute('band_show', [
                'id'   => $band->getId(),
                'slug' => $band->getSlug(),
            ]);
        }

        return $this->render('band/edit.html.twig', [
            'form' => $form->createView(),
            'band' => $band,
        ]);
    }

    #[Route('/{id}-{slug}/delete', name: 'band_delete', methods: ['POST'])]
    public function delete(Band $band, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $band->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($band);
        $em->flush();

        $this->addFlash('success', 'Band deleted');

        return $this->redirectToRoute('band_list');
    }
}

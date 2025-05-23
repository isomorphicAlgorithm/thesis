<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Band;
use App\Entity\Musician;
use App\Entity\Rating;
use App\Form\MusicianType;
use App\Repository\AlbumRepository;
use App\Repository\BandRepository;
use App\Repository\MusicianRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/albums')]
class AlbumController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'album_show', methods: ['GET', 'POST'])]
    public function show(Album $album, string  $slug, Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $expectedSlug = $album->getSlug();

        if ($slug != $expectedSlug) {
            return $this->redirectToRoute('album_show', [
                'id' => $album->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        $user = $security->getUser();

        // Handle rating form submission
        if ($request->isMethod('POST') && $user) {
            $ratingValue = (int) $request->request->get('rating');

            //dd($ratingValue); die;

            // Ensure rating is between 0 and 100
            if ($ratingValue >= 0 && $ratingValue <= 100) {
                // Check if user already rated this album
                $rating = $em->getRepository(Rating::class)->findOneBy([
                    'user' => $user,
                    'album' => $album,
                ]);

                if (!$rating) {
                    $rating = new Rating();
                    $rating->setUser($user);
                    //$rating->initializeTimestamps(); // manually initialize to avoid null access
                    $rating->setAlbum($album);
                }

                $rating->setRatingScore($ratingValue);

                $em->persist($rating);
                $em->flush();

                $this->addFlash('success', 'Your rating has been saved.');
            } else {
                $this->addFlash('error', 'Invalid rating. Must be between 0 and 100.');
            }

            return $this->redirectToRoute('album_show', ['id' => $album->getId(), 'slug' => $album->getSlug()]);
        }

        // Get user's existing rating (if any)
        $userRating = null;
        if ($user) {
            $existing = $em->getRepository(Rating::class)->findOneBy([
                'user' => $user,
                'album' => $album,
            ]);
            if ($existing) {
                $userRating = $existing->getRatingScore();
            }
        }

        return $this->render('album/show.html.twig', [
            'album' => $album,
            'userRating' => $userRating,
        ]);
    }

    #[Route('/', name: 'album_list')]
    public function list(
        Request $request,
        AlbumRepository $albumRepository,
        BandRepository $bandRepository,
        MusicianRepository $musicianRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
        $sort = $request->query->get('sort', 'title');
        $order = $request->query->get('order', 'asc');
        $bandId = $request->query->get('band_id');
        $musicianId = $request->query->get('musician_id');

        $allowedSorts = ['title', 'created'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'title';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $qb = $em->getRepository(Album::class)->createQueryBuilder('a');

        $band = null;
        // Filtering by band
        if ($bandId) {
            $band = $em->getRepository(Band::class)->findOneBy(['id' => $bandId]);

            $qb->join('a.bands', 'b')
                ->andWhere('b.id = :bandId')
                ->setParameter('bandId', $bandId);
        }

        $musician = null;
        // Filtering by musician
        if ($musicianId) {
            $musician = $em->getRepository(Musician::class)->findOneBy(['id' => $musicianId]);

            $qb->join('a.musicians', 'm')
                ->andWhere('m.id = :musicianId')
                ->setParameter('musicianId', $musicianId);
        }

        // Sorting
        switch ($sort) {
            case 'created':
                $qb->orderBy('a.createdAt', $order);
                break;
            case 'title':
            default:
                $qb->orderBy('a.title', $order);
                break;
        }

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            30,
            [
                'sortFieldWhitelist' => [],
                'defaultSortFieldName' => null,
                'defaultSortDirection' => null,
                'sortFieldParameterName' => 'ignore',
                'sortDirectionParameterName' => 'ignore',
            ]
        );

        return $this->render('album/list.html.twig', [
            'band' => $band,
            'musician' => $musician,
            'pagination' => $pagination,
            'bands' => $bandRepository->findAll(),
            'musicians' => $musicianRepository->findAll(),
            'selectedBandId' => $bandId,
            'selectedMusicianId' => $musicianId,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Band;
use App\Entity\Genre;
use App\Entity\Musician;
use App\Entity\Rating;
use App\Form\MusicianType;
use App\Repository\AlbumRepository;
use App\Repository\BandRepository;
use App\Repository\GenreRepository;
use App\Repository\MusicianRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/albums')]
class AlbumController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'album_show', methods: ['GET', 'POST'])]
    public function show(Album $album, string $slug, Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $expectedSlug = $album->getSlug();

        if ($slug !== $expectedSlug) {
            return $this->redirectToRoute('album_show', [
                'id' => $album->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        $user = $security->getUser();

        if ($request->isMethod('POST') && $user) {
            $ratingRaw = $request->request->get('rating');
            $reviewInput = $request->request->get('review');
            $hasRating = is_numeric($ratingRaw);
            $hasReview = $reviewInput !== null && trim($reviewInput) !== '';

            // Proceed only if at least one of rating or review is submitted
            if ($hasRating || $hasReview) {
                $ratingEntity = $em->getRepository(Rating::class)->findOneBy([
                    'user' => $user,
                    'album' => $album,
                ]);

                if (!$ratingEntity) {
                    $ratingEntity = new Rating();
                    $ratingEntity->setUser($user);
                    $ratingEntity->setAlbum($album);
                }

                // Update rating if it's submitted and valid
                if ($hasRating) {
                    $ratingValue = (int) $ratingRaw;
                    if ($ratingValue >= 0 && $ratingValue <= 100) {
                        $ratingEntity->setRatingScore($ratingValue);

                        $em->persist($ratingEntity);
                        $em->flush();

                        $this->addFlash('success', 'Your rating has been saved');
                    } else {
                        $this->addFlash('error', 'Invalid rating. Must be between 0 and 100');

                        return $this->redirectToRoute('album_show', [
                            'id' => $album->getId(),
                            'slug' => $album->getSlug(),
                        ]);
                    }
                }

                if ($hasReview) {
                    if (mb_strlen(trim($reviewInput)) < 600) {
                        $this->addFlash('error', 'Your review must be at least 600 characters long');

                        return $this->redirectToRoute('album_show', [
                            'id' => $album->getId(),
                            'slug' => $album->getSlug(),
                        ]);
                    } else {
                        $ratingEntity->setReview($reviewInput);

                        $em->persist($ratingEntity);
                        $em->flush();

                        $this->addFlash('success', 'Your review has been saved');
                    }
                }
            } else {
                $this->addFlash('error', 'Please provide a rating or a review.');
            }

            return $this->redirectToRoute('album_show', [
                'id' => $album->getId(),
                'slug' => $album->getSlug(),
            ]);
        }

        // Fetch existing rating by this user
        $userRating = null;
        $userReview = null;
        $existingRating = null;

        if ($user) {
            $existing = $em->getRepository(Rating::class)->findOneBy([
                'user' => $user,
                'album' => $album,
            ]);
            if ($existing) {
                $userRating = $existing->getRatingScore();
                $userReview = $existing->getReview();
                $existingRating = $existing;
            }
        }

        $visibleReviews = []; // Max 4
        $additionalCount = 0;
        // All ratings for display
        $allRatings = $em->getRepository(Rating::class)->findBy(['album' => $album]);

        $filtered = array_filter($allRatings, function (Rating $r) {
            $review = $r->getReview();
            return $review !== null && trim($review) !== '';
        });

        if (isset($existing) && !in_array($existing, $filtered, true) && $existing->getReview() !== null && trim($existing->getReview()) !== '') {
            array_unshift($filtered, $existing);
        }

        $visibleReviews = array_slice($filtered, 0, 4);
        $additionalCount = max(count($filtered) - 4, 0);

        $ratingScores = $em->createQueryBuilder()
            ->select('r')
            ->from(Rating::class, 'r')
            ->where('r.album = :album')
            ->andWhere('r.rating_score IS NOT NULL')
            ->setParameter('album', $album)
            ->getQuery()
            ->getResult();

        return $this->render('album/show.html.twig', [
            'album' => $album,
            'userRating' => $userRating,
            'userReview' => $userReview,
            'ratings' => $allRatings,
            'ratingScores' => $ratingScores,
            'existingRating' => $existingRating,
            'visibleReviews' => $visibleReviews,
            'additionalReviewCount' => $additionalCount,
        ]);
    }

    #[Route('/', name: 'album_list')]
    public function list(Request $request, GenreRepository $genreRepository, BandRepository $bandRepository, MusicianRepository $musicianRepository, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'title');
        $order = $request->query->get('order', 'asc');
        $bandId = $request->query->get('band_id');
        $musicianId = $request->query->get('musician_id');
        $genreId = $request->query->get('genre_id');

        $allowedSorts = ['title', 'created', 'most_reviewed', 'highest_avg_rating', 'reviewed_by_me', 'rated_by_me', 'highest_my_rating'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'title';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $user = $this->getUser();

        $qb = $em->getRepository(Album::class)->createQueryBuilder('a');

        // Filters
        if ($bandId) {
            $band = $bandRepository->find($bandId);
            $qb->join('a.bands', 'b')->andWhere('b.id = :bandId')->setParameter('bandId', $bandId);
        } else {
            $band = null;
        }

        if ($musicianId) {
            $musician = $musicianRepository->find($musicianId);
            $qb->join('a.musicians', 'm')->andWhere('m.id = :musicianId')->setParameter('musicianId', $musicianId);
        } else {
            $musician = null;
        }

        if ($genreId) {
            $genre = $genreRepository->find($genreId);
            $qb->join('a.genres', 'g')->andWhere('g.id = :genreId')->setParameter('genreId', $genreId);
        } else {
            $genre = null;
        }

        // Sorting
        switch ($sort) {
            case 'most_reviewed':
                $qb
                    ->leftJoin('a.ratings', 'r_reviewed')
                    ->andWhere('r_reviewed.review IS NOT NULL')
                    ->addSelect('COUNT(r_reviewed.id) AS HIDDEN reviewCount')
                    ->groupBy('a.id')
                    ->addOrderBy('reviewCount', $order);
                break;

            case 'highest_avg_rating':
                $qb
                    ->leftJoin('a.ratings', 'r_avg')
                    ->andWhere('r_avg.rating_score IS NOT NULL')
                    ->addSelect('AVG(r_avg.rating_score) AS HIDDEN avgRating')
                    ->groupBy('a.id')
                    ->addOrderBy('avgRating', $order);
                break;

            case 'reviewed_by_me':
                if ($this->getUser()) {
                    $qb
                        ->join('a.ratings', 'r_reviewed_by_me')
                        ->andWhere('r_reviewed_by_me.user = :user')
                        ->andWhere('r_reviewed_by_me.review IS NOT NULL')
                        ->setParameter('user', $this->getUser());
                }
                break;

            case 'rated_by_me':
                if ($this->getUser()) {
                    $qb
                        ->join('a.ratings', 'r_rated_by_me')
                        ->andWhere('r_rated_by_me.user = :user')
                        ->andWhere('r_rated_by_me.rating_score IS NOT NULL')
                        ->setParameter('user', $this->getUser());
                }
                break;

            case 'highest_my_rating':
                if ($this->getUser()) {
                    $qb
                        ->join('a.ratings', 'r_my_score')
                        ->andWhere('r_my_score.user = :user')
                        ->andWhere('r_my_score.rating_score IS NOT NULL')
                        ->setParameter('user', $this->getUser())
                        ->addSelect('r_my_score.rating_score AS HIDDEN myRating')
                        ->addOrderBy('myRating', $order);
                }
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
        // Collect user ratings & reviews for albums on the current page if user is logged in
        $userRatingsFormatted = [];
        $userReviewsFormatted = [];

        if ($user) {
            $albumIds = array_map(fn($album) => $album->getId(), iterator_to_array($pagination));

            if (count($albumIds) > 0) {
                $ratingRepo = $em->getRepository(Rating::class);

                $userRatings = $ratingRepo->createQueryBuilder('r')
                    ->select('IDENTITY(r.album) AS album_id, r.rating_score')
                    ->andWhere('r.user = :user')
                    ->andWhere('r.album IN (:albumIds)')
                    ->andWhere('r.rating_score IS NOT NULL')
                    ->setParameter('user', $user)
                    ->setParameter('albumIds', $albumIds)
                    ->getQuery()
                    ->getResult();

                $userReviews = $ratingRepo->createQueryBuilder('r')
                    ->select('IDENTITY(r.album) AS album_id')
                    ->andWhere('r.user = :user')
                    ->andWhere('r.review IS NOT NULL')
                    ->andWhere('r.album IN (:albumIds)')
                    ->setParameter('user', $user)
                    ->setParameter('albumIds', $albumIds)
                    ->getQuery()
                    ->getResult();

                $userRatingsFormatted = [];
                foreach ($userRatings as $row) {
                    $userRatingsFormatted[$row['album_id']] = $row['rating_score'];
                }

                $userReviewsFormatted = [];
                foreach ($userReviews as $row) {
                    $userReviewsFormatted[$row['album_id']] = true;
                }
            }
        }

        return $this->render('album/list.html.twig', [
            'band' => $band,
            'musician' => $musician,
            'genre' => $genre,
            'pagination' => $pagination,
            'bands' => $bandRepository->findAll(),
            'musicians' => $musicianRepository->findAll(),
            'genres' => $genreRepository->findAll(),
            'selectedBandId' => $bandId,
            'selectedMusicianId' => $musicianId,
            'selectedGenreId' => $genreId,
            'totalAlbums' => $pagination->getTotalItemCount(),
            'userRatings' => $userRatingsFormatted,
            'userReviews' => $userReviewsFormatted,
        ]);
    }

    #[Route('/reviews', name: 'reviews_list', methods: ['GET'])]
    public function reviews(Request $request, AlbumRepository $albumRepository, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $filters = $request->query->all();

        $album = null;

        $albumId = $request->query->get('album');
        $albumId = filter_var($albumId, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if ($albumId !== null) {
            $album = $albumRepository->find($albumId);
            if (!$album) {
                throw $this->createNotFoundException('Album not found');
            }
        }

        // Sorting logic
        $sort = $request->query->get('sort', 'created');
        $order = $request->query->get('order', 'desc');
        $allowedSorts = ['review', 'created'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) $sort = 'created';
        if (!in_array($order, $allowedOrders)) $order = 'desc';

        // Base query
        $qb = $em->getRepository(Rating::class)->createQueryBuilder('r')
            ->andWhere('r.review IS NOT NULL');

        if ($album !== null) {
            $qb->andWhere('r.album = :album')->setParameter('album', $album);
        }

        // Optional filter for current user's reviews
        $myReviewsFilter = filter_var($request->query->get('my_reviews'), FILTER_VALIDATE_BOOLEAN);

        $user = $this->getUser();
        if ($myReviewsFilter) {
            if (!$user) {
                throw $this->createAccessDeniedException('You must be logged in to see your reviews.');
            }
            $qb->andWhere('r.user = :user')->setParameter('user', $user);
        }

        // Sorting
        $qb->orderBy(
            $sort === 'review' ? 'r.review' : 'r.createdAt',
            $order
        );

        // Paginate results
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            9,
            [
                'sortFieldWhitelist' => [],
                'defaultSortFieldName' => null,
                'defaultSortDirection' => null,
                'sortFieldParameterName' => 'ignore',
                'sortDirectionParameterName' => 'ignore',
            ]
        );

        // Format for JSON (needed in js)
        $reviewsData = array_map(function ($rating) {
            $user = $rating->getUser();
            return [
                'review' => $rating->getReview(),
                'ratingScore' => $rating->getRatingScore(),
                'createdAt' => $rating->getCreatedAt()->format('M d, Y'),
                'user' => ['username' => $user ? $user->getUsername() : 'Unknown'],
            ];
        }, $pagination->getItems());

        return $this->render('album/reviews.html.twig', [
            'album' => $album,
            'pagination' => $pagination,
            'reviewsData' => json_encode($reviewsData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
            'sort' => $sort,
            'order' => $order,
            'totalReviews' => $pagination->getTotalItemCount(),
            'albums' => $albumRepository->findAll(),
            'selectedAlbumId' => $album ? $album->getId() : null,
            'myReviewsFilter' => $myReviewsFilter,
            'filters' => $filters,
        ]);
    }

    #[Route('/{albumId}/rating/{id}/delete-score', name: 'album_delete_score', methods: ['POST'])]
    public function deleteRatingScore(int $albumId, Rating $rating, EntityManagerInterface $em, Request $request): RedirectResponse
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-rating-score' . $rating->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $user = $this->getUser();
        if ($user !== $rating->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete this rating score');
        }

        // Nullify rating score only, keep review intact
        $rating->setRatingScore(null);
        $em->persist($rating);
        $em->flush();

        $album = $em->getRepository(Album::class)->findOneBy([
            'id' => $albumId
        ]);

        $this->addFlash('success', 'Rating score deleted.');

        return $this->redirectToRoute('album_show', ['id' => $albumId, 'slug' => $album->getSlug()]);
    }

    #[Route('/{albumId}/rating/{id}/delete-review', name: 'album_delete_review', methods: ['POST'])]
    public function deleteReview(int $albumId, Rating $rating, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-rating-review' . $rating->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $user = $this->getUser();
        if ($user !== $rating->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete this review');
        }

        // Nullify review only, keep rating score intact
        $rating->setReview(null);
        $em->persist($rating);
        $em->flush();

        $this->addFlash('success', 'Review deleted.');

        $redirectUrl = $request->request->get('redirectUrl');

        // Fallback redirect URL if not provided or invalid
        if (!$redirectUrl || !filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
            $album = $em->getRepository(Album::class)->find($albumId);
            if (!$album) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Album not found.',
                ], 404);
            }
            $redirectUrl = $this->generateUrl('album_show', [
                'id' => $albumId,
                'slug' => $album->getSlug(),
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'redirectUrl' => $redirectUrl,
        ]);
    }
}

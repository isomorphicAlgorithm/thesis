<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Band;
use App\Entity\Favorite;
use App\Entity\Genre;
use App\Entity\Musician;
use App\Entity\Song;
use App\Form\MusicianType;
use App\Repository\AlbumRepository;
use App\Repository\BandRepository;
use App\Repository\FavoriteRepository;
use App\Repository\GenreRepository;
use App\Repository\MusicianRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/songs')]
class SongController extends AbstractController
{
    #[Route('/{id}-{slug}', name: 'song_show', methods: ['GET'])]
    public function show(Song $song, string  $slug, Security $security, FavoriteRepository $favoriteRepository): Response
    {
        $expectedSlug = $song->getSlug();

        if ($slug != $expectedSlug) {
            return $this->redirectToRoute('song_show', [
                'id' => $song->getId(),
                'slug' => $expectedSlug,
            ]);
        }

        $user = $security->getUser();

        $isFavorite = false;

        $favoriteCount = $favoriteRepository->count(['song' => $song]);

        if ($user) {
            $isFavorite = $song->isFavoritedByUser($user);
        }

        return $this->render('song/show.html.twig', [
            'song' => $song,
            'isFavorite' => $isFavorite,
            'favoriteCount' => $favoriteCount,
        ]);
    }

    #[Route('/song/{id}/favorite-toggle', name: 'song_favorite_toggle', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function toggleFavorite(int $id, Request $request, EntityManagerInterface $em, UserInterface $user): JsonResponse
    {
        // Simple CSRF check — token name fixed
        $csrfToken = $request->request->get('_csrf_token');

        if (!$this->isCsrfTokenValid('favorite' . $id, $csrfToken)) {
            return new JsonResponse(['error' => 'Invalid CSRF token'], 400);
        }

        $song = $em->getRepository(Song::class)->find($id);
        if (!$song) {
            return new JsonResponse(['error' => 'Song not found'], 404);
        }

        $favoriteRepo = $em->getRepository(Favorite::class);

        $existingFavorite = $favoriteRepo->findOneBy([
            'user' => $user,
            'song' => $song,
        ]);

        if ($existingFavorite) {
            $em->remove($existingFavorite);
            $em->flush();

            return new JsonResponse(['isFavorite' => false]);
        }

        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setSong($song);

        $em->persist($favorite);
        $em->flush();

        return new JsonResponse(['isFavorite' => true]);
    }

    #[Route('/', name: 'song_list')]
    public function list(Request $request, GenreRepository $genreRepository, AlbumRepository $albumRepository, BandRepository $bandRepository, MusicianRepository $musicianRepository, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort', 'title');
        $order = $request->query->get('order', 'asc');
        $bandId = $request->query->get('band_id');
        $musicianId = $request->query->get('musician_id');
        $albumId = $request->query->get('album_id');
        $genreId = $request->query->get('genre_id');
        $favoritedOnly = $request->query->get('favorited_only') === '1';

        $user = $this->getUser();

        $allowedSorts = ['title', 'created'];
        $allowedOrders = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'title';
        }

        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }

        $qb = $em->getRepository(Song::class)->createQueryBuilder('s');

        $band = null;
        // Filtering by band
        if ($bandId) {
            $band = $em->getRepository(Band::class)->findOneBy(['id' => $bandId]);

            $qb->join('s.bands', 'b')
                ->andWhere('b.id = :bandId')
                ->setParameter('bandId', $bandId);
        }

        $musician = null;
        // Filtering by musician
        if ($musicianId) {
            $musician = $em->getRepository(Musician::class)->findOneBy(['id' => $musicianId]);

            $qb->join('s.musicians', 'm')
                ->andWhere('m.id = :musicianId')
                ->setParameter('musicianId', $musicianId);
        }

        $album = null;
        // Filtering by album
        if ($albumId) {
            $album = $em->getRepository(Album::class)->findOneBy(['id' => $albumId]);

            $qb->join('s.albums', 'a')
                ->andWhere('a.id = :albumId')
                ->setParameter('albumId', $albumId);
        }

        $genre = null;

        if ($genreId) {
            $genre = $em->getRepository(Genre::class)->find($genreId);
            $qb->join('s.genres', 'g')
                ->andWhere('g.id = :genreId')
                ->setParameter('genreId', $genreId);
        }

        if ($favoritedOnly && $user) {
            // Join favorites to filter by current user's favorites
            $qb->innerJoin('s.favorites', 'f', 'WITH', 'f.user = :user')
                ->setParameter('user', $user);
        }

        $favoritedSongIds = [];

        if ($user) {
            $favoriteRepo = $em->getRepository(Favorite::class);
            $favorites = $favoriteRepo->findBy(['user' => $user]);
            // Collect song IDs that are favorited by user
            $favoritedSongIds = array_map(fn($fav) => $fav->getSong()->getId(), $favorites);
        }
        // Sorting
        switch ($sort) {
            case 'created':
                $qb->orderBy('s.createdAt', $order);
                break;
            case 'title':
            default:
                $qb->orderBy('s.title', $order);
                break;
        }

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            35,
            [
                'sortFieldWhitelist' => [],
                'defaultSortFieldName' => null,
                'defaultSortDirection' => null,
                'sortFieldParameterName' => 'ignore',
                'sortDirectionParameterName' => 'ignore',
            ]
        );

        return $this->render('song/list.html.twig', [
            'band' => $band,
            'musician' => $musician,
            'album' => $album,
            'pagination' => $pagination,
            'genre' => $genre,
            'genres' => $genreRepository->findAll(),
            'bands' => $bandRepository->findAll(),
            'musicians' => $musicianRepository->findAll(),
            'albums' => $albumRepository->findAll(),
            'selectedBandId' => $bandId,
            'selectedMusicianId' => $musicianId,
            'selectedAlbumId' => $albumId,
            'selectedGenreId' => $genreId,
            'favoritedOnly' => $favoritedOnly,
            'favoritedSongIds' => $favoritedSongIds,
            'totalSongs' =>  $pagination->getTotalItemCount(),
        ]);
    }
}

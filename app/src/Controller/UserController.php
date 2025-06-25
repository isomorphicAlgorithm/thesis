<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\RatingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class UserController extends AbstractController
{
    #[Route('/user/{id}-{slug}', name: 'app_profile')]
    //#[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[IsGranted('ROLE_USER')]
    public function profile(int $id, string $slug, UserRepository $userRepository, RatingRepository $ratingRepository, FavoriteRepository $favoriteRepository, Security $security): Response
    {
        $user = $userRepository->find($id); // only by ID

        $this->denyAccessUnlessGranted('INLINE_EDIT', $user);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        if ($user->getSlug() !== $slug) {
            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId(),
                'slug' => $user->getSlug(),
            ], 302);
        }

        /** @var \App\Entity\User|null $currentUser */
        $currentUser = $security->getUser();

        $ratingScores = [];
        $reviews = [];

        $ratingScores = $ratingRepository->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.rating_score IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        $reviews = $ratingRepository->createQueryBuilder('r')
            ->where('r.user = :user')
            ->andWhere('r.review IS NOT NULL')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        $userRatingScores = [];
        $userReviews = [];

        foreach ($ratingScores as $rating) {
            if ($rating->getAlbum()) {
                $userRatingScores[$rating->getAlbum()->getId()] = $rating->getRatingScore();
            }
        }

        foreach ($reviews as $review) {
            if ($review->getAlbum()) {
                $userReviews[$review->getAlbum()->getId()] = true;
            }
        }

        // Determine if it's the currently logged-in user's own profile
        $isOwnProfile = $currentUser && $currentUser->getId() == $user->getId();

        $coverImage = $user->getCoverImage();
        $coverImagePath = 'uploads/cover_images/' . ($coverImage ?: 'default.png');

        $filesystem = new Filesystem();
        if (!$coverImage || !$filesystem->exists($coverImagePath)) {
            $coverImagePath = 'uploads/cover_images/default.png';
        }

        if (!$isOwnProfile) {
            return $this->redirectToRoute('app_profile', [
                'id' => $currentUser->getId(),
                'slug' => $currentUser->getSlug(),
            ]);
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'ratingScores' => $ratingScores,
            'reviews' => $reviews,
            'isOwnProfile' => $isOwnProfile,
            'userRatingScores' => $userRatingScores,
            'userReviews' => $userReviews,
            'coverImagePath' => $coverImagePath,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function editProfile(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('INLINE_EDIT', $user);

        $uploadedFile = $request->files->get('cover_image');

        if ($uploadedFile) {
            $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
            $uploadedFile->move($this->getParameter('profile_pictures_directory'), $newFilename);
            $user->setCoverImage($newFilename);
            $em->flush();
        }

        return $this->redirectToRoute('app_profile', ['id' => $user->getId(), 'slug' => $user->getSlug()]);
    }

    #[Route('/profile/update-bio', name: 'update_bio', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateBio(Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('INLINE_EDIT', $user);

        $data = json_decode($request->getContent(), true);

        if (!isset($data['bio']) || !is_string($data['bio'])) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid input'], 400);
        }

        $user->setBio($data['bio']);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}

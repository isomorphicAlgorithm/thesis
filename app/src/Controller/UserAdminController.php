<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/users')]
#[IsGranted('ROLE_ADMIN')]
class UserAdminController extends AbstractController
{
    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $query = $userRepository->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            25
        );

        return $this->render('admin/index.html.twig', [
            'pagination' => $pagination,
            'totalUsers' => $userRepository->count([]),
            'totalAdmins' => $userRepository->countByRole('ROLE_ADMIN'),
            'totalVerified' => $userRepository->count(['is_verified' => true]),
            'totalUnverified' => $userRepository->count(['is_verified' => false]),
        ]);
    }

    #[Route('/{id}/toggle-admin', name: 'admin_user_toggle_admin', methods: ['POST'])]
    public function toggleAdmin(User $user, EntityManagerInterface $em): Response
    {
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {
            $user->setRoles(array_values(array_diff($roles, ['ROLE_ADMIN'])));

            $this->addFlash('success', $user->getUsername() . ' demoted from admin.');
        } else {
            $roles[] = 'ROLE_ADMIN';

            $user->setRoles(array_unique($roles));

            $this->addFlash('success', $user->getUsername() . ' promoted to admin.');
        }

        $em->flush();

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/admin/users/{id}/toggle-verification', name: 'admin_user_toggle_verification', methods: ['POST'])]
    public function toggleVerification(Request $request, User $user, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('warning', 'You cannot change your own verification status.');

            return $this->redirectToRoute('admin_user_index');
        }

        $user->setIsVerified(!$user->isVerified());

        $em->flush();

        $this->addFlash('success', sprintf('User "%s" has been %s.', $user->getUsername(), $user->isVerified() ? 'verified' : 'unverified'));

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'You cannot delete yourself.');

            return $this->redirectToRoute('admin_user_index');
        }

        $em->remove($user);

        $em->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('admin_user_index');
    }
}
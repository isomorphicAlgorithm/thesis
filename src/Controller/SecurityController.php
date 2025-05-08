<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Security\EmailVerifier;

class SecurityController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier
    ) {}

    #[Route('/verify/email/{id}', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, AuthorizationCheckerInterface $authChecker, int $id): Response
    {
        try {
            // EmailVerifier decodes the token, fetches user, and verifies
            $this->emailVerifier->handleEmailConfirmation($request, $id);

            $this->addFlash('success', 'Your email address has been verified. You can now log in.');

            return $this->redirectToRoute('app_login');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }
        /*
        if (!$authChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Store the current URL and redirect to login
            $this->addFlash('info', 'Please log in to confirm your email.');

            $session = $request->getSession();
            $session->set('_security.main.target_path', $request->getUri());

            return $this->redirectToRoute('app_login');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
        /*
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');*/
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

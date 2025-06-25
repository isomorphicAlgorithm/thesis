<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Security\EmailVerifier;

class SecurityController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier
    ) {}

    #[Route('/verify/email/{id}', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, int $id): Response
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
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

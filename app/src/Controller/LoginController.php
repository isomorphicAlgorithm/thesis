<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Form\LoginFormType;
use App\Form\ResendVerificationType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $loginFormAuthenticator, EmailVerifier $emailVerifier): Response
    {
        if ($this->getUser()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            return $this->redirectToRoute('app_profile', [
                'id' => $user->getId(),
                'slug' => $user->getSlug(),
            ]);
        }

        $form = $this->createForm(LoginFormType::class);
        $resendVerificationForm = $this->createForm(ResendVerificationType::class);

        $form->handleRequest($request);
        $resendVerificationForm->handleRequest($request);

        $showResendLink = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $form->addError(new FormError('No account with this email has been found'));
            } else if ($user && !$passwordHasher->isPasswordValid($user, $password)) {
                $form->addError(new FormError('Invalid password'));
            } else if ($user && !$user->isVerified()) {
                $showResendLink = true;

                $request->getSession()->set('unverified_email', $email);

                $form->addError(new FormError('Please verify your email before logging in'));
            } else {
                return $userAuthenticator->authenticateUser(
                    $user,
                    $loginFormAuthenticator,
                    $request
                );
            }
        }

        // Handle resendVerificationForm separately
        if ($resendVerificationForm->isSubmitted() && $resendVerificationForm->isValid()) {
            $session = $request->getSession();
            $unverifiedEmail = $session->get('unverified_email');

            if ($unverifiedEmail) {
                $user = $userRepository->findOneBy(['email' => $unverifiedEmail]);

                if ($user && !$user->isVerified()) {
                    $emailVerifier->sendEmailConfirmation(
                        'app_verify_email',
                        $user,
                        (new TemplatedEmail())
                            ->from('banditosecure@gmail.com')
                            ->to($unverifiedEmail)
                            ->subject('Please Confirm your Email')
                            ->htmlTemplate('registration/confirmation_email.html.twig')
                    );

                    $this->addFlash('success', 'Verification email resent.');
                }

                $session->remove('unverified_email');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'resendVerificationForm' => $resendVerificationForm->createView(),
            'showResendLink' => $showResendLink
        ]);
    }
}

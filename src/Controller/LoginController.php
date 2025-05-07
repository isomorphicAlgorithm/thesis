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
use App\Form\LoginForm;
use App\Security\LoginFormAuthenticator;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $loginFormAuthenticator): Response
    {
        $form = $this->createForm(LoginForm::class);

        $form->handleRequest($request);
/*
        if ($form->isSubmitted() && !$form->isValid()) {
            if ($request->isXmlHttpRequest()) {
                return new Response($this->renderView('security/login_inner.html.twig', [
                    'loginForm' => $form,
                ]), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
*/
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
                $form->addError(new FormError('Invalid email or password'));
            } else {
                return $userAuthenticator->authenticateUser(
                    $user,
                    $loginFormAuthenticator,
                    $request
                );
            }
        }

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
        ]);
        /*
        // For normal (non-AJAX) or valid submission, render full template
        return $this->render('security/login.html.twig', [
            'loginForm' => $form,
        ]);
        */
    }
}

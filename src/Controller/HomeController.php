<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();

        if ($user) {
            // Logged-in user
            $roleInfo = $user->getRoles(); // e.g. ['ROLE_USER']
            $username = $user->getUserIdentifier(); // or getEmail()
        } else {
            // Anonymous visitor
            $roleInfo = ['GUEST'];
            $username = null;
        }

        return $this->render('home/index.html.twig', [
            'username' => $username,
            'roles' => $roleInfo,
        ]);
    }
}
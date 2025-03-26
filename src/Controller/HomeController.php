<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        // On peut passer des variables à la vue Twig
        return $this->render('home/index.html.twig', [
            'pseudo' => $this->getUser() ? $this->getUser()->getUsername() : "-VISITOR-", // Vérifie si un utilisateur est connecté
            'perm' => $this->getUser() ? $this->getUser()->getRoles() : [0],
        ]);
    }
}

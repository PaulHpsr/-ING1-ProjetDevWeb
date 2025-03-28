<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LoginController extends AbstractController
{
    private $security;


    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        // Vérifier si l'utilisateur est déjà connecté


        return $this->render('accueil_login.html.twig');
    }
}





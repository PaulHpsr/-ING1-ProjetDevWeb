<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur d'authentification, s'il existe
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier email saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Géré automatiquement par Symfony
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par Symfony.');
    }
}

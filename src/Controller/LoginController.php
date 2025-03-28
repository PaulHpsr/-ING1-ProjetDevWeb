<?php

// src/Controller/LoginController.php
namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Créer une instance du formulaire
        $form = $this->createForm(LoginType::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Initialisation de la variable $error
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire (email et mot de passe)
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];

            // Ici, tu peux ajouter la logique pour vérifier si les identifiants sont valides.
            // Typiquement, Symfony gère cela via son système de sécurité.

            // Pour l'exemple, on va rediriger l'utilisateur si le mot de passe est correct
            if ($email === 'admin@domain.com' && $password === 'password') {
                // Si l'authentification réussit, tu peux rediriger l'utilisateur
                return $this->redirectToRoute('app_home');
            } else {
                // Si l'authentification échoue, on affiche une erreur
                $this->addFlash('error', 'Identifiants incorrects');
            }
        }

        // Rendre la vue avec le formulaire et l'erreur
        return $this->render('login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,  // Passer l'erreur à la vue pour l'afficher si nécessaire
        ]);
    }
}


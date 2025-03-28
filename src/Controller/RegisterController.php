<?php

// src/Controller/RegistrationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        // Gestion de la soumission du formulaire
        if ($request->isMethod('POST')) {
            // Logique pour enregistrer l'utilisateur dans la base de données
            // Par exemple, récupérer les données du formulaire et créer un utilisateur

            // Simuler l'enregistrement de l'utilisateur
            $pseudo = $request->request->get('pseudo');
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');
            $email = $request->request->get('email');
            $motdepasse = $request->request->get('motdepasse');
            $datenaissance = $request->request->get('datenaissance');
            $genre = $request->request->get('genre');

            // Vous pouvez ajouter ici la logique pour créer un utilisateur dans la base de données
            // Par exemple avec Doctrine ORM

            // Une fois l'utilisateur créé, rediriger vers la page de connexion ou une autre page
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register.html.twig');
    }
}

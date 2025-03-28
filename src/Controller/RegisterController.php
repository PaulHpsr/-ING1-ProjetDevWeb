<?php

// src/Controller/RegisterController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType; // Formulaire d'inscription
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Crée un nouvel utilisateur
        $user = new User();

        // Crée le formulaire d'inscription basé sur l'entité User
        $form = $this->createForm(RegisterType::class, $user);

        // Gérer les soumissions du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe avec UserPasswordHasherInterface
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword() // Récupérer le mot de passe en texte brut depuis le formulaire
            );

            // Associer le mot de passe haché à l'utilisateur
            $user->setPassword($hashedPassword);

            // Enregistrer l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger l'utilisateur après l'enregistrement
            return $this->redirectToRoute('app_login');  // Redirige vers la page de connexion
        }

        // Rendre la vue avec le formulaire
        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete_user')]
    public function delete(User $user, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le mot de passe en clair fourni pour confirmation
        $plaintextPassword = $request->get('password');  // Par exemple, récupéré d'un formulaire de confirmation

        // Vérifier si le mot de passe fourni est valide
        if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            throw new AccessDeniedHttpException('Le mot de passe est incorrect');
        }

        // Si le mot de passe est valide, procéder à la suppression
        $entityManager->remove($user);
        $entityManager->flush();

        // Retourner une réponse après la suppression
        return new Response('Utilisateur supprimé avec succès');
    }
}


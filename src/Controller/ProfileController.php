<?php

// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType; // Assure-toi que ce formulaire est bien créé
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    // Afficher le profil
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Si aucun utilisateur n'est connecte -> exception
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à votre profil.');
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }

#[Route('/profile/edit', name: 'app_profile_edit')]
public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{
    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour modifier votre profil.');
    }

    // Créer le formulaire pour éditer le profil
    $form = $this->createForm(ProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Recup le mot de passe de base
        $plainPassword = $form->get('plainPassword')->getData();

        // Si un nouveau mot de passe a été saisi, le hasher et le définir sur l'utilisateur
        if ($plainPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

    // Gérer l'upload de la photo de profil
    $profilePictureFile = $form->get('profilePicture')->getData();
    if ($profilePictureFile) {
        $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();

        try {
            $profilePictureFile->move(
                $this->getParameter('profile_pictures_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            $this->addFlash('error', 'Erreur lors de l\'upload de la photo de profil');
            return $this->redirectToRoute('app_register');
        }

        $user->setProfilePicture($newFilename);
    }
        // Mettre à jour les autres informations de l'utilisateur
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre profil a été mis à jour.');

        return $this->redirectToRoute('app_profile');
    }

    return $this->render('profil/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
}

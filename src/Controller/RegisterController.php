<?php

// src/Controller/RegisterController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            // Vérification si l'email existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {
                // Si l'email existe déjà, afficher un message d'erreur
                $this->addFlash('error', 'Cet email est déjà utilisé, veuillez en choisir un autre.');
                return $this->redirectToRoute('app_register');  // Rediriger l'utilisateur vers la page d'inscription
            }

            // Hacher le mot de passe avec UserPasswordHasherInterface
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword() // Récupérer le mot de passe en texte brut depuis le formulaire
            );

            // Associer le mot de passe haché à l'utilisateur
            $user->setPassword($hashedPassword);

            // Gérer l'upload de la photo de profil
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();

                // Déplace le fichier dans le répertoire de destination
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the error
                    $this->addFlash('error', 'Erreur lors de l\'upload de la photo de profil');
                    return $this->redirectToRoute('app_register');
                }

                // Mettre à jour la photo de profil dans l'entité
                $user->setProfilePicture($newFilename);
            }

            // Définir les points de l'utilisateur à 0 (nouveau membre)
            $user->setPoints(0);

            // Définir le niveau d'expérience à "débutant"
            $user->setExperienceLevel("débutant");

            // Définir le rôle par défaut à "ROLE_SIMPLE"
            $user->setRoles(['ROLE_SIMPLE']);

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
}





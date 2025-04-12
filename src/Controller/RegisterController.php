<?php

// src/Controller/RegisterController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        EmailService $emailService
    ): Response {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification si l'email existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', 'Cet email est déjà utilisé, veuillez en choisir un autre.');
                return $this->render('register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Vérification si le nom d'utilisateur existe déjà
            $existingUsername = $entityManager->getRepository(User::class)->findOneBy(['username' => $user->getUsername()]);
            if ($existingUsername) {
                $this->addFlash('error', 'Ce pseudo est déjà utilisé, veuillez en choisir un autre.');
                return $this->render('register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Définir le statut de l'utilisateur à "pending"
            $user->setStatus('pending');

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

            // Définir les points de l'utilisateur à 0 et son niveau d'expérience à "débutant"
            $user->setPoints(0);
            $user->setExperienceLevel("débutant");

            

            // Définir le rôle par défaut à "ROLE_SIMPLE"
            $user->setRoles(['ROLE_SIMPLE']);

            // Ajout de la validation dans le contrôleur ou dans le formulaire
$today = new \DateTime();
$minDate = new \DateTime('-18 years');
if ($user->getBirthdate() > $minDate) {
    $this->addFlash('error', 'Vous devez avoir au moins 18 ans pour vous inscrire.');
    return $this->render('register.html.twig', [
        'form' => $form->createView(),
    ]);
}


            // Enregistrer l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();



            // Envoyer un email à l'utilisateur pour confirmer son inscription
            $emailService->sendEmail(
                $user->getEmail(),
                'Confirmation de votre inscription',
                'Merci de vous être inscrit. Votre compte est actuellement en attente de validation par un administrateur.'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

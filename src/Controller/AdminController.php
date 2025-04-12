<?php


// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Signalement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\SignalementRepository;


class AdminController extends AbstractController
{
    // afficher les users
    #[Route('/admin/utilisateurs', name: 'admin_users')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        // recup les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/admin/bannir/{id}', name: 'admin_ban_user', methods: ['POST'])]
    public function banUser(User $user, EntityManagerInterface $entityManager, SignalementRepository $signalementRepository): Response
    {
        // supp les signalements
        $signalements = $signalementRepository->findBy(['reportedUser' => $user]);
        
        foreach ($signalements as $signalement) {
            $entityManager->remove($signalement);
        }
        
        // delete l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();
    
    
        return $this->redirectToRoute('admin_users');
    }
    
    

    //Liste signalement
    #[Route('/admin/signalements', name: 'admin_signalements')]
    public function listSignalements(EntityManagerInterface $entityManager): Response
    {

        $signalements = $entityManager->getRepository(Signalement::class)->findAll();

        return $this->render('admin/list_signalements.html.twig', [
            'signalements' => $signalements,
        ]);
    }

    // supp un signalement
    #[Route('/admin/signalement/supprimer/{id}', name: 'admin_supprimer_signalement')]
    public function deleteSignalement(Signalement $signalement, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($signalement);
        $entityManager->flush();




        return $this->redirectToRoute('admin_signalements');
    }

    #[Route('/admin/validate/{id}', name: 'admin_validate_user')]
    public function validateUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérification de l'existence de l'utilisateur
        if (!$user || $user->getStatus() !== 'pending') {
            return $this->redirectToRoute('admin_verification');
        }


        $user->setStatus('active');
        $entityManager->persist($user);
        $entityManager->flush();



        return $this->redirectToRoute('admin_verification');
    }

    #[Route('/admin/verification', name: 'admin_verification')]
    public function verification(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs avec le statut 'pending'
        $pendingUsers = $entityManager->getRepository(User::class)->findBy(['status' => 'pending']);

        return $this->render('admin/verification_admin.html.twig', [
            'pendingUsers' => $pendingUsers,
        ]);
    }
}

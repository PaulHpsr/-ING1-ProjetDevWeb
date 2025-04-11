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
    // Afficher la liste des utilisateurs
    #[Route('/admin/utilisateurs', name: 'admin_users')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/admin/bannir/{id}', name: 'admin_ban_user', methods: ['POST'])]
    public function banUser(User $user, EntityManagerInterface $entityManager, SignalementRepository $signalementRepository): Response
    {
        // Supprimez tous les signalements liés à cet utilisateur
        $signalements = $signalementRepository->findBy(['reportedUser' => $user]);
        
        foreach ($signalements as $signalement) {
            $entityManager->remove($signalement);
        }
        
        // Supprimer l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();
    
        // Message de succès
        $this->addFlash('success', 'Utilisateur banni et ses signalements supprimés avec succès !');
    
        return $this->redirectToRoute('admin_users');
    }
    
    

    // Afficher la liste des signalements
    #[Route('/admin/signalements', name: 'admin_signalements')]
    public function listSignalements(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les signalements de la base de données
        $signalements = $entityManager->getRepository(Signalement::class)->findAll();

        return $this->render('admin/list_signalements.html.twig', [
            'signalements' => $signalements,
        ]);
    }

    // Supprimer un signalement
    #[Route('/admin/signalement/supprimer/{id}', name: 'admin_supprimer_signalement')]
    public function deleteSignalement(Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        // Supprimer le signalement de la base de données
        $entityManager->remove($signalement);
        $entityManager->flush();

        // Afficher un message flash
        $this->addFlash('success', 'Signalement supprimé avec succès.');

        // Rediriger vers la liste des signalements
        return $this->redirectToRoute('admin_signalements');
    }

    #[Route('/admin/validate/{id}', name: 'admin_validate_user')]
    public function validateUser(User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérification de l'existence de l'utilisateur
        if (!$user || $user->getStatus() !== 'pending') {
            return $this->redirectToRoute('admin_verification');
        }

        // Mise à jour du statut de l'utilisateur
        $user->setStatus('active');
        $entityManager->persist($user);
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Utilisateur validé avec succès.');

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

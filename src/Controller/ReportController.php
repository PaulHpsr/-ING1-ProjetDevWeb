<?php

// src/Controller/ReportController.php
namespace App\Controller;

use App\Entity\Signalement;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportController extends AbstractController
{
    #[Route('/report/{id}', name: 'app_report_user', methods: ['POST'])]
    public function report(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {

        $reason = $request->request->get('reason');

        if (empty($reason)) {
            $this->addFlash('error', 'Veuillez renseigner la raison du signalement.');
            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        // Créer un signalement et l'associer à l'utilisateur signalé
        $signalement = new Signalement();
        $signalement->setReportedUser($user);
        $signalement->setReason($reason);

        // Sauvegarder le signalement dans la base de données
        $entityManager->persist($signalement);
        $entityManager->flush();



        return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
    }
}

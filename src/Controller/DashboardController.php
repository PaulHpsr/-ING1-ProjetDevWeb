<?php

// src/Controller/DashboardController.php
namespace App\Controller;

use App\Entity\ObjetConnecte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authChecker): Response
    {
        // Vérifier si l'utilisateur a le rôle 'ROLE_COMPLEX' ou 'ROLE_ADMIN'
        if (!$this->isGranted('ROLE_COMPLEX') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé');
        }
        

        // Récupérer les objets connectés
        $objets = $entityManager->getRepository(ObjetConnecte::class)->findAll();

        // Consommation énergétique quotidienne et hebdomadaire
        $dailyConsumption = $this->getDailyConsumption($entityManager);
        $weeklyConsumption = $this->getWeeklyConsumption($entityManager);

        return $this->render('dashboard.html.twig', [
            'objets' => $objets,
            'dailyConsumption' => $dailyConsumption,
            'weeklyConsumption' => $weeklyConsumption,
        ]);
    }

    // Fonction pour obtenir la consommation énergétique quotidienne des objets connectés
    private function getDailyConsumption(EntityManagerInterface $entityManager)
    {
        $today = new \DateTime('today');
        $startOfDay = $today->setTime(0, 0, 0);

        $consumption = $entityManager->getRepository(ObjetConnecte::class)->createQueryBuilder('o')
            ->select('SUM(o.consommationEnergetique)')
            ->where('o.derniereInteraction > :startOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->getQuery()
            ->getSingleScalarResult();

        return $consumption ?: 0;
    }

    // Fonction pour obtenir la consommation énergétique hebdomadaire des objets connectés
    private function getWeeklyConsumption(EntityManagerInterface $entityManager)
    {
        $today = new \DateTime('today');
        $startOfWeek = $today->modify('monday this week')->setTime(0, 0, 0);

        $consumption = $entityManager->getRepository(ObjetConnecte::class)->createQueryBuilder('o')
            ->select('SUM(o.consommationEnergetique)')
            ->where('o.derniereInteraction > :startOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->getQuery()
            ->getSingleScalarResult();

        return $consumption ?: 0;
    }
}

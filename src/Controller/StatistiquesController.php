<?php

// src/Controller/StatistiquesController.php
namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\ObjetsConnectes;
use App\Entity\Signalement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatistiquesController extends AbstractController
{
    
    #[Route('/admin/statistiques', name: 'admin_statistiques')]
public function statistiques(EntityManagerInterface $entityManager): Response
{
    // Récupérer des données pour les rapports
    $totalUsers = $entityManager->getRepository(User::class)->count([]);
    $totalObjets = $entityManager->getRepository(ObjetsConnectes::class)->count([]);
    $totalSignalements = $entityManager->getRepository(Signalement::class)->count([]);

    // Calculer la consommation énergétique totale pour les objets dont l'état n'est pas "déconnecté"
    $qb = $entityManager->createQueryBuilder();
    $totalConsommationEnerg = $qb->select('SUM(o.consommationEnergetique) as totalConsumption')
        ->from(ObjetsConnectes::class, 'o')
        ->where('o.etat != :etat')
        ->setParameter('etat', 'déconnecté')
        ->getQuery()
        ->getSingleScalarResult();

    // Calculer le nombre et la consommation énergétique des objets "déconnectés"
    $qbDisconnected = $entityManager->createQueryBuilder();
    $disconnectedData = $qbDisconnected->select('COUNT(o) as disconnectedCount, SUM(o.consommationEnergetique) as disconnectedConsumption')
        ->from(ObjetsConnectes::class, 'o')
        ->where('o.etat = :etat')
        ->setParameter('etat', 'déconnecté')
        ->getQuery()
        ->getSingleResult();

    $disconnectedCount = $disconnectedData['disconnectedCount'] ?? 0;
    $disconnectedConsumption = $disconnectedData['disconnectedConsumption'] ?? 0;

    $tauxConnexion = $this->getTauxConnexion($entityManager);

    return $this->render('admin/statistiques.html.twig', [
        'totalUsers'              => $totalUsers,
        'totalObjets'             => $totalObjets,
        'totalSignalements'       => $totalSignalements,
        'totalConsommationEnerg'  => $totalConsommationEnerg,
        'disconnectedCount'       => $disconnectedCount,
        'disconnectedConsumption' => $disconnectedConsumption,
        'tauxConnexion'           => $tauxConnexion,
    ]);
}


    
    private function getTauxConnexion(EntityManagerInterface $entityManager): float
    {
        $connectedUsers = $entityManager->getRepository(User::class)->findBy(['status' => 'active']);
        $totalUsers = $entityManager->getRepository(User::class)->count([]);

        return ($totalUsers > 0) ? (count($connectedUsers) / $totalUsers) * 100 : 0;
    }

// Exporter les rapports en CSV
#[Route('/admin/statistiques/export-csv', name: 'admin_export_csv')]
public function exportCSV(EntityManagerInterface $entityManager): Response
{
    // Récupérer les utilisateurs et les compteurs de base
    $users = $entityManager->getRepository(User::class)->findAll();
    $totalUsers = $entityManager->getRepository(User::class)->count([]);
    $totalObjets = $entityManager->getRepository(ObjetsConnectes::class)->count([]);
    $totalSignalements = $entityManager->getRepository(Signalement::class)->count([]);

    // Calculer la consommation énergétique totale pour les objets dont l'état n'est pas "déconnecté"
    $qb = $entityManager->createQueryBuilder();
    $totalConsommationEnerg = $qb->select('SUM(o.consommationEnergetique) as totalConsumption')
        ->from(ObjetsConnectes::class, 'o')
        ->where('o.etat != :etat')
        ->setParameter('etat', 'déconnecté')
        ->getQuery()
        ->getSingleScalarResult();

    // Calculer le nombre et la consommation énergétique des objets "déconnectés"
    $qbDisconnected = $entityManager->createQueryBuilder();
    $disconnectedData = $qbDisconnected->select('COUNT(o) as disconnectedCount, SUM(o.consommationEnergetique) as disconnectedConsumption')
        ->from(ObjetsConnectes::class, 'o')
        ->where('o.etat = :etat')
        ->setParameter('etat', 'déconnecté')
        ->getQuery()
        ->getSingleResult();

    $disconnectedCount = $disconnectedData['disconnectedCount'] ?? 0;
    $disconnectedConsumption = $disconnectedData['disconnectedConsumption'] ?? 0;

    // Exemple d'appel à une méthode pour calculer le taux de connexion
    $tauxConnexion = $this->getTauxConnexion($entityManager);

    // Générer le contenu du fichier CSV
    $csvContent = "Statistique, Valeur\n";
    $csvContent .= sprintf(
        "Total utilisateurs,%d\nTotal objets,%d\nTotal signalements,%d\nTotal consommation énergétique,%.2f\nTotal objets déconnectés,%d\nTotal consommation énergétique économisée,%.2f\nTaux de connexion,%s\n\n",
        $totalUsers,
        $totalObjets,
        $totalSignalements,
        $totalConsommationEnerg,
        $disconnectedCount,
        $disconnectedConsumption,
        $tauxConnexion
    );

    // Ajouter les informations des utilisateurs
    $csvContent .= "ID,Pseudo,Email,Points,Niveau\n";
    foreach ($users as $user) {
        $csvContent .= sprintf(
            "%d,%s,%s,%d,%s\n",
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getPoints(),
            $user->getExperienceLevel()
        );
    }

    $response = new Response($csvContent);
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="rapport_utilisateurs.csv"');

    return $response;
}


// Exporter les rapports en PDF
#[Route('/admin/statistiques/export-pdf', name: 'admin_export_pdf')]
public function exportPDF(EntityManagerInterface $entityManager): Response
{

    $dompdf = new Dompdf();

    // Récupérer les utilisateurs et les statistiques
    $users = $entityManager->getRepository(User::class)->findAll();
    $totalUsers = $entityManager->getRepository(User::class)->count([]);
    $totalObjets = $entityManager->getRepository(ObjetsConnectes::class)->count([]);
    $totalSignalements = $entityManager->getRepository(Signalement::class)->count([]);
    // Calculer la consommation énergétique totale pour les objets dont l'état n'est pas "déconnecté"
    $qb = $entityManager->createQueryBuilder();
    $totalConsommationEnerg = $qb->select('SUM(o.consommationEnergetique) as totalConsumption')
        ->from(ObjetsConnectes::class, 'o')
        ->where('o.etat != :etat')
        ->setParameter('etat', 'déconnecté')
        ->getQuery()
        ->getSingleScalarResult();

    // Calculer le nombre et la consommation énergétique des objets "déconnectés"
    $qbDisconnected = $entityManager->createQueryBuilder();
    $disconnectedData = $qbDisconnected->select('COUNT(o) as disconnectedCount, SUM(o.consommationEnergetique) as disconnectedConsumption')
        ->from(ObjetsConnectes::class, 'o')
        ->where('o.etat = :etat')
        ->setParameter('etat', 'déconnecté')
        ->getQuery()
        ->getSingleResult();

    $disconnectedCount = $disconnectedData['disconnectedCount'] ?? 0;
    $disconnectedConsumption = $disconnectedData['disconnectedConsumption'] ?? 0;
    $tauxConnexion = $this->getTauxConnexion($entityManager);


    $html = '<h1>Rapport des utilisateurs</h1>';
    $html .= '<h2>Statistiques</h2>';
    $html .= sprintf(
        "<p>Total utilisateurs : %d</p>
        <p>Total objets : %d</p>
        <p>Total signalements : %d</p>
        <p>Total consommation énergétique : %.2f kWh</p>
        <p>Total objets déconnectés : %.2f kWh</p>
        <p>Total consommation énergétique économisée: %.2f kWh</p>
        <p>Taux de connexion : %s</p>",
        $totalUsers,
        $totalObjets,
        $totalSignalements,
        $totalConsommationEnerg,
        $disconnectedCount,
        $disconnectedConsumption,
        $tauxConnexion
    );

    $html .= '<h2>Informations des utilisateurs</h2>';
    foreach ($users as $user) {
        $html .= sprintf(
            "<p>ID: %d - Pseudo: %s - Email: %s - Points: %d - Niveau: %s</p>",
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getPoints(),
            $user->getExperienceLevel()
        );
    }


    $dompdf->loadHtml($html);


    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();


    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="rapport_utilisateurs.pdf"');

    return $response;
}
}

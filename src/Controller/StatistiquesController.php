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
    // Page principale des statistiques pour les admins
    #[Route('/admin/statistiques', name: 'admin_statistiques')]
    public function statistiques(EntityManagerInterface $entityManager): Response
    {
        // Récupérer des données pour les rapports
        $totalUsers = $entityManager->getRepository(User::class)->count([]);
        $totalObjets = $entityManager->getRepository(ObjetsConnectes::class)->count([]);
        $totalSignalements = $entityManager->getRepository(Signalement::class)->count([]);

        // Consommation énergétique totale
        $totalConsommationEnerg = $entityManager->getRepository(ObjetsConnectes::class)
            ->createQueryBuilder('o')
            ->select('SUM(o.consommationEnergetique)')
            ->getQuery()
            ->getSingleScalarResult();

        // Statistiques des connexions des utilisateurs (par exemple, taux de connexion)
        $tauxConnexion = $this->getTauxConnexion($entityManager);

        return $this->render('admin/statistiques.html.twig', [
            'totalUsers'              => $totalUsers,
            'totalObjets'             => $totalObjets,
            'totalSignalements'       => $totalSignalements,
            'totalConsommationEnerg'  => $totalConsommationEnerg,
            'tauxConnexion'           => $tauxConnexion,
        ]);
    }

    // Fonction pour obtenir le taux de connexion des utilisateurs
    private function getTauxConnexion(EntityManagerInterface $entityManager): float
    {
        $connectedUsers = $entityManager->getRepository(User::class)->findBy(['status' => 'active']);
        $totalUsers = $entityManager->getRepository(User::class)->count([]);

        return ($totalUsers > 0) ? (count($connectedUsers) / $totalUsers) * 100 : 0;
    }

    // Route pour exporter les rapports en CSV
    #[Route('/admin/statistiques/export-csv', name: 'admin_export_csv')]
    public function exportCSV(EntityManagerInterface $entityManager): Response
    {
        // Générer les données à exporter
        $users = $entityManager->getRepository(User::class)->findAll();

        // Générer le fichier CSV
        $csvContent = "ID,Pseudo,Email,Points,Niveau\n";
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

        // Préparer la réponse avec le fichier CSV
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport_utilisateurs.csv"');

        return $response;
    }

    // Route pour exporter les rapports en PDF
    #[Route('/admin/statistiques/export-pdf', name: 'admin_export_pdf')]
    public function exportPDF(EntityManagerInterface $entityManager): Response
    {
        // Instancier Dompdf
        $dompdf = new Dompdf();

        // Récupérer les données des utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        // Construire le contenu HTML pour le PDF
        $html = '<h1>Rapport des utilisateurs</h1>';
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

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Configurer les options de Dompdf
        $options = new Options([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled'         => true,
        ]);
        $dompdf->setOptions($options);

        // Rendre le PDF
        $dompdf->render();

        // Générer la réponse PDF
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport_utilisateurs.pdf"');

        return $response;
    }
}

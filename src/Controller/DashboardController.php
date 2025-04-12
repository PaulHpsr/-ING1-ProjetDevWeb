<?php
// src/Controller/EnergyConsumptionController.php
namespace App\Controller;

use App\Entity\ObjetsConnectes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // 1. Consommation totale pour les objets connectés (état != "déconnecté")
        $qbTotal = $entityManager->createQueryBuilder();
        $qbTotal->select('SUM(o.consommationEnergetique) as totalConsumption')
            ->from(ObjetsConnectes::class, 'o')
            ->where('o.etat != :etat')
            ->setParameter('etat', 'déconnecté');
        $totalConsumption = $qbTotal->getQuery()->getSingleScalarResult();

        // 2. Répartition de la consommation par type (pour les objets connectés)
        $qbGroup = $entityManager->createQueryBuilder();
        $qbGroup->select('o.type, SUM(o.consommationEnergetique) as consumption')
            ->from(ObjetsConnectes::class, 'o')
            ->where('o.etat != :etat')
            ->groupBy('o.type')
            ->setParameter('etat', 'déconnecté');
        $groupedData = $qbGroup->getQuery()->getResult();

        // 3. Récupérer le nombre d'objets déconnectés et la somme de leur consommation
        $qbDisconnected = $entityManager->createQueryBuilder();
        $qbDisconnected->select('COUNT(o) as disconnectedCount, SUM(o.consommationEnergetique) as disconnectedConsumption')
            ->from(ObjetsConnectes::class, 'o')
            ->where('o.etat = :etat')
            ->setParameter('etat', 'déconnecté');
        $disconnectedData = $qbDisconnected->getQuery()->getOneOrNullResult();
        
        $disconnectedCount = $disconnectedData['disconnectedCount'] ?? 0;

        // si aucun enregistrement -> 0
        $disconnectedConsumption = $disconnectedData['disconnectedConsumption'] ?? 0;


        // Ajout de POINTS
    $user = $this->getUser();

    if ($user && $user->getPoints() < 10) {
        // Ajout de 0.25 point pour visiter cette page
     $user->setPoints($user->getPoints() + 0.25);

     // Utiliser l'EntityManager correctement
     $entityManager->persist($user);
        $entityManager->flush();
    }


        return $this->render('dashboard.html.twig', [
            'totalConsumption'       => $totalConsumption,
            'groupedData'            => $groupedData,
            'disconnectedCount'      => $disconnectedCount,
            'disconnectedConsumption'=> $disconnectedConsumption,
        ]);
    }
}

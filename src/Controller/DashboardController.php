<?php

namespace App\Controller;

use App\Entity\ObjetsConnectes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Vérification des rôles (Accès limité aux utilisateurs avec ROLE_COMPLEX ou ROLE_ADMIN)
        if (!$this->isGranted('ROLE_COMPLEX') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé');
        }

        // Début de la semaine et début de la journée
        $startOfWeek = (new \DateTime('monday this week'))->setTime(0, 0, 0);
        $startOfDay = (new \DateTime('today'))->setTime(0, 0, 0);

        // Repository des objets connectés
        $repository = $entityManager->getRepository(ObjetsConnectes::class);

        // Récupération des données
        $dailyConsumption = $repository->getDailyConsumption($startOfDay);
        $weeklyConsumption = $repository->getWeeklyConsumption($startOfWeek);
        $consumptionPerDay = $this->groupConsumptionPerDay($repository, $startOfWeek);

        // Rendu de la vue
        return $this->render('dashboard.html.twig', [
            'dailyConsumption' => $dailyConsumption,
            'weeklyConsumption' => $weeklyConsumption,
            'consumptionPerDay' => $consumptionPerDay, // Données pour le graphique hebdomadaire
        ]);
    }

    /**
     * Regroupe la consommation énergétique par jour à l'échelle du serveur PHP.
     *
     * @param ObjetsConnectesRepository $repository
     * @param \DateTimeInterface $startOfWeek
     * @return array
     */
    private function groupConsumptionPerDay($repository, \DateTimeInterface $startOfWeek): array
    {
        // Récupérer les données des objets connectés depuis la base
        $results = $repository->createQueryBuilder('o')
            ->select('o.derniereInteraction as interactionDate, SUM(o.consommationEnergetique) as consumption')
            ->where('o.derniereInteraction >= :startOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->groupBy('o.derniereInteraction') // Regroupe les dates complètes
            ->orderBy('o.derniereInteraction', 'ASC')
            ->getQuery()
            ->getResult();

        // Grouper par jour à l'échelle du serveur
        $groupedResults = [];
        foreach ($results as $result) {
            $day = $result['interactionDate']->format('Y-m-d'); // Transforme en jour seulement
            if (!isset($groupedResults[$day])) {
                $groupedResults[$day] = 0;
            }
            $groupedResults[$day] += $result['consumption']; // Additionne les consommations par jour
        }

        return $groupedResults;
    }
}

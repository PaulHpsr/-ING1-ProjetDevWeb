<?php

namespace App\Repository;

use App\Entity\ObjetsConnectes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<ObjetsConnectes>
 *
 * @method ObjetsConnectes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjetsConnectes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjetsConnectes[]    findAll()
 * @method ObjetsConnectes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjetsConnectesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetsConnectes::class);
    }

    /**
     * Recherche dans les champs "nom" et "type" des ObjetsConnectés.
     *
     * @param string $searchTerm Le terme à rechercher.
     * @return ObjetsConnectes[] Retourne un tableau d'objets ObjetsConnectés correspondant à la recherche.
     */
    public function findByNameOrTypeLike(string $searchTerm): array
    {
        return $this->createQueryBuilder('o')
            ->where('LOWER(o.nom) LIKE LOWER(:searchTerm) OR LOWER(o.type) LIKE LOWER(:searchTerm)')
            ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtenir la consommation énergétique quotidienne des objets connectés.
     *
     * @param \DateTimeInterface $startOfDay Le début de la journée.
     * @return float La consommation énergétique quotidienne totale.
     */
    public function getDailyConsumption(\DateTimeInterface $startOfDay): float
    {
        return $this->createQueryBuilder('o')
            ->select('SUM(o.consommationEnergetique)')
            ->where('o.derniereInteraction >= :startOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->getQuery()
            ->getSingleScalarResult() ?: 0;
    }

    /**
     * Obtenir la consommation énergétique hebdomadaire des objets connectés.
     *
     * @param \DateTimeInterface $startOfWeek Le début de la semaine.
     * @return float La consommation énergétique hebdomadaire totale.
     */
    public function getWeeklyConsumption(\DateTimeInterface $startOfWeek): float
    {
        return $this->createQueryBuilder('o')
            ->select('SUM(o.consommationEnergetique)')
            ->where('o.derniereInteraction >= :startOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->getQuery()
            ->getSingleScalarResult() ?: 0;
    }

    /**
     * Obtenir la consommation énergétique par jours sur une semaine (utile pour les graphiques).
     *
     * @param \DateTimeInterface $startOfWeek Le début de la semaine.
     * @return array Un tableau associatif où la clé est le jour et la valeur la consommation énergétique.
     */
    public function getConsumptionPerDay(\DateTimeInterface $startOfWeek): array
    {
        return $this->createQueryBuilder('o')
            ->select('DATE(o.derniereInteraction) as day, SUM(o.consommationEnergetique) as consumption')
            ->where('o.derniereInteraction >= :startOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

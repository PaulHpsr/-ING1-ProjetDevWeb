<?php

namespace App\Repository;

use App\Entity\ObjetsConnectes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


class ObjetsConnectesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetsConnectes::class);
    }

    /**
     * cherche "nom" et "type" des ObjetsConnectés.
     *
     * @param string $searchTerm Le terme à rechercher
     * @return ObjetsConnectes[] 
     */
    public function findByNameOrTypeLike(string $searchTerm): array
    {
        return $this->createQueryBuilder('o')
            ->where('LOWER(o.nom) LIKE LOWER(:searchTerm) OR LOWER(o.type) LIKE LOWER(:searchTerm)')
            ->setParameter('searchTerm', '%' . strtolower($searchTerm) . '%')
            ->getQuery()
            ->getResult();
    }

}

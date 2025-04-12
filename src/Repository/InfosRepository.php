<?php

namespace App\Repository;

use App\Entity\Infos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class InfosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infos::class);
    }

 
    public function findByTitleOrContentLike(string $searchTerm): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.content LIKE :searchTerm OR i.title LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }


    public function findLatestInfos(int $limit = 5): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.publishDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

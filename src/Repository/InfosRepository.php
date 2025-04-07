<?php

namespace App\Repository;

use App\Entity\Infos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Infos>
 *
 * @method Infos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Infos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Infos[]    findAll()
 * @method Infos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infos::class);
    }

    /**
     * Recherche dans les champs "content" et "title" des infos.
     *
     * @param string $searchTerm Le terme à rechercher.
     * @return Infos[] Retourne un tableau d'objets Infos correspondant à la recherche.
     */
    public function findByTitleOrContentLike(string $searchTerm): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.content LIKE :searchTerm OR i.title LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les dernières informations publiées (limitées à $limit éléments).
     *
     * @param int $limit Nombre maximum de résultats à retourner
     * @return Infos[] Retourne un tableau d'objets Infos
     */
    public function findLatestInfos(int $limit = 5): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.publishDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

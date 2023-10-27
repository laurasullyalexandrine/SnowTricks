<?php

namespace App\Repository;

use App\Entity\Trickgroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trickgroup>
 *
 * @method Trickgroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trickgroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trickgroup[]    findAll()
 * @method Trickgroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickgroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trickgroup::class);
    }

    public function save(Trickgroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trickgroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

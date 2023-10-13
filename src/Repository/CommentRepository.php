<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Undocumented function
     *
     * @param Trick $trick
     * @param integer $page
     * @param integer $maxResult
     * @return array
     */
    public function findCommentsPaginated(Trick $trick, int $page, int $maxResult = 10): array
    {
        $maxResult = abs($maxResult);

        $result = [];

        $query = $this->createQueryBuilder('c')
            ->addSelect('t')
            ->leftJoin('c.trick', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $trick->getSlug())
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($maxResult)
            ->setFirstResult(($page * $maxResult) - $maxResult);

        $query->getQuery()->getResult();


        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        // Vérifier qu'il y des données
        if (empty($data)) {
            return $result;
        }

        // Calculer le nombre de pages
        $pages =  ceil($paginator->count() / $maxResult);

        // Remplir le tableau $result
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['firstResult'] = $page;
        $result['limit'] = $maxResult;

        return $result;
    }

    //    /**
    //     * @return Comment[] Returns an array of Comment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comment
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\Hashtags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hashtags|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hashtags|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hashtags[]    findAll()
 * @method Hashtags[]    findTopTags()
 * @method Hashtags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashtagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hashtags::class);
    }

    // /**
    //  * @return Hashtags[]
    //  */
    
    public function findTopTags()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM hashtags
            ORDER BY views DESC LIMIT 10;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
    

    /*
    public function findOneBySomeField($value): ?Hashtags
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

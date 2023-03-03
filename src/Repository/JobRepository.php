<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Job;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function save(Job $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Job $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int|null $categoryId
     * 
     * return Job[]
     */
    public function findActiveJobs(int $categoryId = null)
    {
        $queryBuilder = $this->createQueryBuilder('j')
                            ->where('j.expiresAt > :date')
                            ->setParameter('date', new DateTime())
                            ->orderBy('j.expiresAt', 'DESC');
        if ($categoryId) {
            $queryBuilder->andWhere('j.category = :categoryId')
            ->setParameter('categoryId', $categoryId);
        }
        
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param int $jobId
     * 
     * @return Job|null
     */
    public function findActiveJob (int $jobId) : ?Job
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->Where('j.id = :jobId')
            ->andWhere('j.expiresAt > :date')
            ->setParameter('jobId', $jobId)
            ->setParameter('date',new DateTime())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Category $category
     * 
     * @return AbstractQuery
     */
    public function getPaginatedActiveJobsByCategoryQuery (Category $category) : AbstractQuery
    {
        return $this->createQueryBuilder('j')
            ->where('j.expiresAt > :date')
            ->andWhere('j.category = :category')
            ->setParameter('category',$category)
            ->setParameter('date', new DateTime())
            ->getQuery();
    }
//    /**
//     * @return Job[] Returns an array of Job objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Job
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

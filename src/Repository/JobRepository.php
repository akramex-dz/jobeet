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
                    ->andWhere('j.activated = :activated')
                    ->setParameter('date', new DateTime())
                    ->setParameter('activated', true)
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
                    ->where('j.id = :id')
                    ->andWhere('j.expiresAt > :date')
                    ->andWhere('j.activated = :activated')
                    ->setParameter('id', $jobId)
                    ->setParameter('date', new DateTime())
                    ->setParameter('activated', true)
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
                    ->where('j.category = :category')
                    ->andWhere('j.expiresAt > :date')
                    ->andWhere('j.activated = :activated')
                    ->setParameter('category', $category)
                    ->setParameter('date', new DateTime())
                    ->setParameter('activated', true)
                    ->getQuery();
    }
}

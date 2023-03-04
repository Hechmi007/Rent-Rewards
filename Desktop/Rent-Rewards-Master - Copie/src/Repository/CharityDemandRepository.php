<?php

namespace App\Repository;

use App\Entity\CharityDemand;
use App\Form\SubmitType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharityDemand>
 *
 * @method CharityDemand|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharityDemand|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharityDemand[]    findAll()
 * @method CharityDemand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharityDemandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharityDemand::class);
    }

    public function save(CharityDemand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CharityDemand $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function search(string $query = '')
    {
        $qb = $this->createQueryBuilder('p');
        
        $qb->where('p.title LIKE :query')
           ->setParameter('query', '%'.$query.'%')
           ->orderBy('p.title', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return CharityDemand[] Returns an array of CharityDemand objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CharityDemand
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

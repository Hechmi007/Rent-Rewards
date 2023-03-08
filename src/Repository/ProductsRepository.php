<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function save(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findEntitiesByString($productname){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e
                FROM App\Entity\Products e
                WHERE e.productname LIKE :productname'
            )
            ->setParameter('productname', '%'.$productname.'%')
            ->getResult();
    }
    public function countProductsByproductname($productname)
{
    return $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->where('p.productname LIKE :productname')
        ->setParameter('productname', '%"'.$productname.'"%')
        ->getQuery()
            ->getSingleScalarResult();
}
public function getNombreAchatsPourCategorie($products_category_id)
    {
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a.id) AS nombre_achats')
            ->join('a.category', 'p')
            ->join('p.productname', 'c')
            ->where('c.id = :products_category_id')
            ->setParameter('products_category_id', $products_category_id)
            ->getQuery();

        $resultat = $query->getSingleScalarResult();

        return $resultat;
    }


    public function findByProductName($productname)
    {
        return $this->createQueryBuilder('q')
        ->createQueryBuilder()
        ->select('p')
        ->from('App\Entity\Products', 'q')
        ->where('p.productname LIKE :productname')
        ->setParameter('productname', '%' . $productname . '%')
        ->getQuery()
        ->getResult();
    
    }
    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getProductDataByCategory()
{
    return $this->createQueryBuilder('product')
        ->select('product.productscategory as category, count(product.id) as product_count')
        ->join('product.productscategory', 'categoryname')
        ->groupBy('product.id')
        ->getQuery()
        ->getResult();
}


//    /**
//     * @return Products[] Returns an array of Products objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Producto>
 *
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    public function add(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findDestacados(int $limit = 8)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.activo = true')
            ->andWhere('p.destacado = true')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findBySearch(string $q)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.activo = true')
            ->andWhere('p.nombre LIKE :q OR p.descripcion LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->orderBy('p.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function search(string $term, int $limit = 20, int $offset = 0)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.activo = true')
            ->andWhere('p.nombre LIKE :term OR p.descripcion LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function countSearch(string $term): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.activo = true')
            ->andWhere('p.nombre LIKE :term OR p.descripcion LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }
//    /**
//     * @return Producto[] Returns an array of Producto objects
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

//    public function findOneBySomeField($value): ?Producto
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

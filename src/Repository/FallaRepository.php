<?php

namespace App\Repository;

use App\Entity\Falla;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Falla>
 *
 * @method Falla|null find($id, $lockMode = null, $lockVersion = null)
 * @method Falla|null findOneBy(array $criteria, array $orderBy = null)
 * @method Falla[]    findAll()
 * @method Falla[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FallaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Falla::class);
    }

    public function save(Falla $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Falla $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findFallasToSelect(): array
    {
        return $this->createQueryBuilder('f')
            ->select('f.idFalla', 'f.nombre') // Selecciona las dos columnas específicas
            ->orderBy('f.idFalla', 'ASC')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Falla[] Returns an array of Falla objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Falla
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

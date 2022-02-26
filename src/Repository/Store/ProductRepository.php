<?php

namespace App\Repository\Store;

use App\Entity\Store\Brand;
use App\Entity\Store\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        return $this->addImage(parent::createQueryBuilder($alias, $indexBy), $alias);
    }

    public function createDetailsQueryBuilder(string $alias = 'p'): QueryBuilder
    {
        return $this->addComments($this->createQueryBuilder($alias), 'p')
            ->addSelect([
                'b',
                'col',
            ])
            ->leftJoin($alias.'.brand', 'b')
            ->leftJoin($alias.'.colors', 'col')
        ;
    }

    public function findOneWithDetails(int $id): ?Product
    {
        return $this->createDetailsQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Product[]
     */
    public function findList(): array
    {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getResult();
    }

    public function findByBrand(Brand $brand): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.brand', 'b')
            ->where('b = :brand')
            ->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function findLastCreated(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function findMostCommented(): array
    {
        return $this->addComments($this->createQueryBuilder('p'))
            ->groupBy('p')
            ->orderBy('COUNT(c)', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    private function addImage(QueryBuilder $qb, string $alias = 'p'): QueryBuilder
    {
        $qb
            ->addSelect('i')
            ->leftJoin($alias.'.image', 'i');

        return $qb;
    }

    private function addComments(QueryBuilder $qb, string $alias = 'p'): QueryBuilder
    {
        $qb
            ->addSelect('c')
            ->leftJoin($alias.'.comments', 'c');

        return $qb;
    }
}

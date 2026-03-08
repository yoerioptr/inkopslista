<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
final class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findOrCreateByName(string $name): Product
    {
        $product = $this->createQueryBuilder('product')
            ->where('LOWER(product.name) = LOWER(:name)')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$product) {
            $product = new Product();
            $product->setName($name);
            $this->getEntityManager()->persist($product);
        }

        return $product;
    }

}

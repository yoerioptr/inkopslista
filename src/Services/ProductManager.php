<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ProductManager
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
        //
    }

    public function getOrCreateProduct(string $name): Product
    {
        $product = $this->productRepository->findByName($name);

        if (!$product) {
            $product = new Product();
            $product->setName($name);
        }

        return $product;
    }
}

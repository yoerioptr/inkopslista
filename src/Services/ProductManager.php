<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;

final readonly class ProductManager
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
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

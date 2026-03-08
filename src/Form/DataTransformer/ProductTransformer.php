<?php

namespace App\Form\DataTransformer;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\DataTransformerInterface;

final readonly class ProductTransformer implements DataTransformerInterface
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
        //
    }

    public function transform(mixed $value): ?string
    {
        return $value instanceof Product
            ? $value->getName()
            : null;
    }

    public function reverseTransform(mixed $value): ?Product
    {
        return $value
            ? $this->productRepository->findOrCreateByName($value)
            : null;
    }
}

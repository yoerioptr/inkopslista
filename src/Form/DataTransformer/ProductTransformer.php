<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\Product;
use App\Services\ProductManager;
use Symfony\Component\Form\DataTransformerInterface;

final readonly class ProductTransformer implements DataTransformerInterface
{
    public function __construct(
        private ProductManager $productManager,
    ) {
    }

    #[\Override]
    public function transform(mixed $value): string
    {
        return $value instanceof Product
            ? $value->getName()
            : '';
    }

    #[\Override]
    public function reverseTransform(mixed $value): ?Product
    {
        return !empty($value) && is_string($value)
            ? $this->productManager->getOrCreateProduct($value)
            : null;
    }
}

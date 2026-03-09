<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Basket;
use App\Repository\BasketItemRepository;

final readonly class BasketManager
{
    public function __construct(
        private BasketItemRepository $basketItemRepository,
    ) {
    }

    public function reorderItems(Basket $basket, array $itemIds): void
    {
        $items = $basket->getItems()->toArray();
        $idMap = [];
        foreach ($items as $item) {
            $idMap[$item->getId()] = $item;
        }

        foreach ($itemIds as $index => $id) {
            if (isset($idMap[$id])) {
                $idMap[$id]->setWeight($index);
            }
        }
    }

    public function createCombinedBasket(): Basket
    {
        $basket = new Basket();

        $items = $this->basketItemRepository->findBy(['inCart' => false]);

        foreach ($items as $item) {
            $basket->addItem($item);
        }

        return $basket;
    }
}

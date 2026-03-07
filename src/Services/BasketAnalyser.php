<?php

namespace App\Services;

use App\Entity\Basket;
use App\Entity\BasketItem;

final class BasketAnalyser
{
    public function numberOfConfirmedItems(Basket $basket): int
    {
        $confirmedItems = 0;

        foreach ($basket->getItems() as $item) {
            if ($item->isInCart()) {
                $confirmedItems++;
            }
        }

        return $confirmedItems;
    }

    public function numberOfUnconfirmedItems(Basket $basket): int
    {
        $unconfirmedItems = 0;

        foreach ($basket->getItems() as $item) {
            if (!$item->isInCart()) {
                $unconfirmedItems++;
            }
        }
        
        return $unconfirmedItems;
    }
}

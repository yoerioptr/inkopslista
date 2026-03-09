<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Product;
use App\Enum\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class BasketFixtures extends Fixture
{
    #[\Override]
    public function load(ObjectManager $manager): void
    {
        $basket = new Basket();
        $basket->setName('Okay 1/1/2026');

        $product = new Product();
        $product->setName('Granola');
        $manager->persist($product);

        $basketItem = new BasketItem();
        $basketItem->setProduct($product);
        $basketItem->setAmount('1.00');
        $basketItem->setUnit(Unit::Kilogram);
        $basketItem->setInCart(false);
        $manager->persist($basketItem);

        $basket->addItem($basketItem);

        $product = new Product();
        $product->setName('Bread');
        $manager->persist($product);

        $basketItem = new BasketItem();
        $basketItem->setProduct($product);
        $basketItem->setAmount('1.00');
        $basketItem->setUnit(Unit::Piece);
        $basketItem->setInCart(true);

        $manager->persist($basketItem);

        $basket->addItem($basketItem);

        $manager->persist($basket);

        $manager->flush();
    }
}

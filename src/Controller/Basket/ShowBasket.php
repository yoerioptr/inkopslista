<?php

declare(strict_types=1);

namespace App\Controller\Basket;

use App\Entity\Basket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ShowBasket extends AbstractController
{
    #[Route('baskets/{basket}', name: 'baskets_show')]
    public function __invoke(Basket $basket): Response
    {
        return $this->render('baskets/details.html.twig', [
            'basket' => $basket,
        ]);
    }
}

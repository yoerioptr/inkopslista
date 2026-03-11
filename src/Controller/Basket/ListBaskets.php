<?php

declare(strict_types=1);

namespace App\Controller\Basket;

use App\Repository\BasketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ListBaskets extends AbstractController
{
    public function __construct(
        private readonly BasketRepository $basketRepository,
    ) {
    }

    #[Route('baskets', name: 'baskets')]
    public function __invoke(): Response
    {
        return $this->render('baskets/list.html.twig', [
            'baskets' => $this->basketRepository->findBy([], ['created' => 'DESC']),
        ]);
    }
}

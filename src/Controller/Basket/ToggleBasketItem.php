<?php

declare(strict_types=1);

namespace App\Controller\Basket;

use App\Dto\ToggleBasketItemRequest;
use App\Entity\Basket;
use App\Entity\BasketItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ToggleBasketItem extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('baskets/{basket}/toggle/{item}', name: 'baskets_toggle_item', methods: ['POST'])]
    public function __invoke(
        Basket $basket,
        BasketItem $item,
        #[MapRequestPayload] ToggleBasketItemRequest $request,
    ): JsonResponse {
        $item->setInCart($request->inCart);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}

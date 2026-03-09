<?php

declare(strict_types=1);

namespace App\Controller\Basket;

use App\Dto\BasketReorderRequest;
use App\Entity\Basket;
use App\Services\BasketManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ReorderBasketItems extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BasketManager $basketManager,
    ) {
    }

    #[Route('baskets/{basket}/reorder', name: 'baskets_reorder', methods: ['POST'])]
    public function __invoke(
        Basket $basket,
        #[MapRequestPayload] BasketReorderRequest $request,
    ): JsonResponse {
        $this->basketManager->reorderItems($basket, $request->ids);

        $this->entityManager->persist($basket);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}

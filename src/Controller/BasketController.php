<?php

namespace App\Controller;

use App\Dto\BasketReorderRequest;
use App\Dto\ToggleBasketItemRequest;
use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Repository\BasketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/baskets')]
final class BasketController extends AbstractController
{
    public function __construct(
        private readonly BasketRepository $basketRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        //
    }

    #[Route('', name: 'baskets')]
    public function index(): Response
    {
        return $this->render('baskets/list.html.twig', [
            'baskets' => $this->basketRepository->findAll(),
        ]);
    }

    #[Route('/{basket}', name: 'baskets_show')]
    public function details(Basket $basket): Response
    {
        return $this->render('baskets/details.html.twig', [
            'basket' => $basket,
        ]);
    }

    #[Route('/{basket}/toggle/{item}', name: 'baskets_toggle_item', methods: ['POST'])]
    public function toggleBasketItem(
        BasketItem $item,
        #[MapRequestPayload] ToggleBasketItemRequest $request,
    ): JsonResponse {
        $item->setInCart($request->inCart);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{basket}/reorder', name: 'baskets_reorder', methods: ['POST'])]
    public function reorderBasket(
        Basket $basket,
        #[MapRequestPayload] BasketReorderRequest $request,
    ): JsonResponse {
        $items = $basket->getItems()->toArray();
        $idMap = [];
        foreach ($items as $item) {
            $idMap[$item->getId()] = $item;
        }

        foreach ($request->ids as $index => $id) {
            if (isset($idMap[$id])) {
                $idMap[$id]->setWeight($index);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}

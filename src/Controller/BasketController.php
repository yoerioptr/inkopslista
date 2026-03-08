<?php

namespace App\Controller;

use App\Dto\BasketReorderRequest;
use App\Dto\ToggleBasketItemRequest;
use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Form\BasketType;
use App\Repository\BasketItemRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
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
        private readonly ProductRepository $productRepository,
        private readonly BasketItemRepository $basketItemRepository,
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

    #[Route('/new', name: 'baskets_new')]
    public function create(Request $request): Response
    {
        $basket = new Basket();
        $basket->setAuthor($this->getUser());

        $form = $this
            ->createForm(BasketType::class, $basket)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($basket);
            $this->entityManager->flush();

            return $this->redirectToRoute('baskets');
        }

        return $this->render('baskets/form.html.twig', [
            'form' => $form,
            'title' => 'New basket',
            'existing_products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/{basket}/edit', name: 'baskets_edit')]
    public function edit(Basket $basket, Request $request): Response
    {
        $form = $this
            ->createForm(BasketType::class, $basket)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('baskets');
        }

        return $this->render('baskets/form.html.twig', [
            'form' => $form,
            'title' => "Edit basket: {$basket->getName()}",
            'existing_products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/new-combined', name: 'baskets_new_combined')]
    public function createCombined(Request $request): Response
    {
        $basket = new Basket();
        $basket->setAuthor($this->getUser());

        $items = $this->basketItemRepository->findBy(['inCart' => false]);
        foreach ($items as $item) {
            $basket->addItem($item);
            $this->entityManager->persist($item);
        }

        $form = $this
            ->createForm(BasketType::class, $basket)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($basket);
            $this->entityManager->flush();

            return $this->redirectToRoute('baskets');
        }

        return $this->render('baskets/form.html.twig', [
            'form' => $form,
            'title' => 'New combined basket',
            'existing_products' => $this->productRepository->findAll(),
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
    public function reorder(
        Basket $basket,
        #[MapRequestPayload] BasketReorderRequest $request,
    ): JsonResponse {
        $items = $basket->items->toArray();
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

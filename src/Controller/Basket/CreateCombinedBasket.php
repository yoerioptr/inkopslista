<?php

namespace App\Controller\Basket;

use App\Entity\Basket;
use App\Form\BasketType;
use App\Repository\BasketItemRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class CreateCombinedBasket extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly BasketItemRepository $basketItemRepository,
    ) {
        //
    }

    #[Route('baskets/new-combined', name: 'baskets_new_combined')]
    public function __invoke(Request $request): Response
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
}

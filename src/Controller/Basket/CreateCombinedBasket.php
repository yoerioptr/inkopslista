<?php

declare(strict_types=1);

namespace App\Controller\Basket;

use App\Form\BasketType;
use App\Message\BasketCreatedNotification;
use App\Repository\ProductRepository;
use App\Services\BasketManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class CreateCombinedBasket extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly BasketManager $basketManager,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('baskets/new-combined', name: 'baskets_new_combined')]
    public function __invoke(Request $request): Response
    {
        $basket = $this->basketManager->createCombinedBasket();
        $basket->setAuthor($this->getUser());

        $form = $this
            ->createForm(BasketType::class, $basket)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($basket);
            $this->entityManager->flush();

            $notification = new BasketCreatedNotification($basket->getId());
            $this->messageBus->dispatch($notification);

            return $this->redirectToRoute('baskets');
        }

        return $this->render('baskets/form.html.twig', [
            'form' => $form,
            'title' => 'New combined basket',
            'existing_products' => $this->productRepository->findAll(),
        ]);
    }
}

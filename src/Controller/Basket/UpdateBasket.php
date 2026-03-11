<?php

declare(strict_types=1);

namespace App\Controller\Basket;

use App\Entity\Basket;
use App\Form\BasketType;
use App\Message\BasketUpdatedNotification;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class UpdateBasket extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('baskets/{basket}/edit', name: 'baskets_edit')]
    public function __invoke(Basket $basket, Request $request): Response
    {
        $form = $this
            ->createForm(BasketType::class, $basket)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $notification = new BasketUpdatedNotification($basket->getId());
            $this->messageBus->dispatch($notification);

            return $this->redirectToRoute('baskets');
        }

        return $this->render('baskets/form.html.twig', [
            'form' => $form,
            'title' => "Edit basket: {$basket->getName()}",
            'existing_products' => $this->productRepository->findAll(),
        ]);
    }
}

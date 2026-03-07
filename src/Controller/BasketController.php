<?php

namespace App\Controller;

use App\Repository\BasketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/baskets')]
final class BasketController extends AbstractController
{
    public function __construct(
        private readonly BasketRepository $basketRepository,
    ) {
        //
    }

    #[Route('', name: 'baskets')]
    public function index(): Response
    {
        return $this->render('basket/list.html.twig', [
            'baskets' => $this->basketRepository->findAll(),
        ]);
    }
}

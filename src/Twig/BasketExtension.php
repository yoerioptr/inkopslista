<?php

namespace App\Twig;

use App\Services\BasketAnalyser;
use Twig\Extension\AbstractExtension;

final class BasketExtension extends AbstractExtension
{
    public function __construct(
        private readonly BasketAnalyser $basketAnalyser,
    ) {
        //
    }

    #[\Override]
    function getfunctions(): array
    {
        return [
            new \Twig\TwigFunction('basket_confirmed', [$this->basketAnalyser, 'numberOfConfirmedItems']),
            new \Twig\TwigFunction('basket_unconfirmed', [$this->basketAnalyser, 'numberOfUnconfirmedItems']),
        ];
    }
}

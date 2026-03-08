<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ToggleBasketItemRequest
{
    public function __construct(
        #[Assert\NotNull] public bool $inCart,
    ) {
        //
    }
}

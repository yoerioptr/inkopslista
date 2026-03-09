<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class BasketReorderRequest
{
    public function __construct(
        public array $ids,
    ) {
    }
}

<?php

namespace App\Dto;

final readonly class BasketReorderRequest
{
    public function __construct(
        public array $ids,
    ) {
        //
    }
}

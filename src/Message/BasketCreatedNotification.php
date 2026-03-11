<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
final readonly class BasketCreatedNotification
{
    public function __construct(
        public int $basketId,
    ) {
    }
}

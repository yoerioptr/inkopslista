<?php

namespace App\Message;

use App\Enum\BasketNotificationType;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
final readonly class SendUserBasketNotification
{
    public function __construct(
        public string $basketId,
        public string $recipientEmail,
        public BasketNotificationType $type,
    ) {
    }
}

<?php

namespace App\MessageHandler;

use App\Enum\BasketNotificationType;
use App\Message\BasketUpdatedNotification;
use App\Message\SendUserBasketNotification;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class BasketUpdatedNotificationHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(BasketUpdatedNotification $message): void
    {
        foreach ($this->userRepository->findAll() as $user) {
            $notification = new SendUserBasketNotification(
                basketId: $message->basketId,
                recipientEmail: $user->getEmail(),
                type: BasketNotificationType::Updated,
            );
            $this->messageBus->dispatch($notification);
        }
    }
}

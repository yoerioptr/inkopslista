<?php

namespace App\MessageHandler;

use App\Message\SendUserBasketNotification;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[AsMessageHandler]
final readonly class SendUserBasketNotificationHandler
{
    public function __construct(private NotifierInterface $notifier)
    {
    }

    public function __invoke(SendUserBasketNotification $sendUserBasketNotification): void
    {
        $notification = new Notification()->content('Your basket has been updated');
        $recipient = new Recipient($sendUserBasketNotification->recipientEmail);
        $this->notifier->send($notification, $recipient);
    }
}

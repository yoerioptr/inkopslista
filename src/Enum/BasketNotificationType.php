<?php

namespace App\Enum;

enum BasketNotificationType: string
{
    case Created = 'created';
    case Updated = 'updated';
}

<?php

declare(strict_types=1);

namespace App\Enumeration;

enum Unit: string
{
    case Piece = 'pcs';
    case Kilogram = 'kg';
    case Gram = 'g';
    case Litre = 'l';
    case Millilitre = 'ml';
    case Centilitre = 'cl';
    case Decilitre = 'dl';

    public function label(): string
    {
        return match ($this) {
            self::Piece => 'piece',
            self::Kilogram => 'kg',
            self::Gram => 'g',
            self::Litre => 'l',
            self::Millilitre => 'ml',
            self::Centilitre => 'cl',
            self::Decilitre => 'dl',
        };
    }
}

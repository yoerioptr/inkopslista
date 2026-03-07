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

    public static function getWeightUnits(): array
    {
        return [
            self::Kilogram,
            self::Gram,
        ];
    }

    public static function getVolumeUnits(): array
    {
        return [
            self::Litre,
            self::Millilitre,
            self::Centilitre,
            self::Decilitre,
        ];
    }
}

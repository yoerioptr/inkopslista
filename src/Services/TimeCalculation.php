<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class TimeCalculation
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function timeElapsedSince(\DateTimeImmutable $dateTime): string
    {
        $now = new \DateTimeImmutable();
        $diff = $now->diff($dateTime);

        $diff->w = (int)floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $units = [
            'y' => 'time.year',
            'm' => 'time.month',
            'w' => 'time.week',
            'd' => 'time.day',
            'h' => 'time.hour',
            'i' => 'time.minute',
        ];

        $parts = [];
        foreach ($units as $k => $v) {
            if ($diff->$k) {
                $parts[] = $this->translator->trans($v, ['%count%' => $diff->$k]);
            }
        }

        if (empty($parts)) {
            return $this->translator->trans('time.just_now');
        }

        return $this->translator->trans('time.ago', ['%time%' => implode(', ', $parts)]);
    }
}

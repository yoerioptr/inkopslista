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

        $weeks = (int)floor($diff->d / 7);
        $days = $diff->d - ($weeks * 7);

        $units = [
            'y' => ['value' => $diff->y, 'label' => 'time.year'],
            'm' => ['value' => $diff->m, 'label' => 'time.month'],
            'w' => ['value' => $weeks, 'label' => 'time.week'],
            'd' => ['value' => $days, 'label' => 'time.day'],
            'h' => ['value' => $diff->h, 'label' => 'time.hour'],
            'i' => ['value' => $diff->i, 'label' => 'time.minute'],
        ];

        $parts = [];
        foreach ($units as $unit) {
            if ($unit['value'] > 0) {
                $parts[] = $this->translator->trans($unit['label'], ['%count%' => $unit['value']]);
            }
        }

        if (empty($parts)) {
            return $this->translator->trans('time.just_now');
        }

        return $this->translator->trans('time.ago', ['%time%' => implode(', ', $parts)]);
    }
}

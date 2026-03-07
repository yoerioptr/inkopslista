<?php

namespace App\Twig;

use App\Services\TimeCalculation;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TimeExtension extends AbstractExtension
{
    public function __construct(
        private readonly TimeCalculation $timeCalculation
    ) {
        //
    }

    #[\Override]
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_elapsed', [$this, 'formatTimeElapsed']),
        ];
    }

    public function formatTimeElapsed(?\DateTimeImmutable $dateTime): string
    {
        return null !== $dateTime
            ? $this->timeCalculation->timeElapsedSince($dateTime)
            : '';
    }
}

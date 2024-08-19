<?php

namespace Clock;

use Psr\Clock\ClockInterface;
use DateTimeImmutable;

class SystemClock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
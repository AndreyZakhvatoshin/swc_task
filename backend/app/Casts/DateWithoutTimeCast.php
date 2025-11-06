<?php

namespace App\Casts;

use Spatie\LaravelData\Casts\Cast;
use DateTimeImmutable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class DateWithoutTimeCast implements Cast
{
    public function cast(
        DataProperty $property,
        mixed $value,
        array $properties,
        CreationContext $context
    ): ?\DateTimeImmutable {
        if ($value === null) {
            return null;
        }

        return \DateTimeImmutable::createFromFormat('Y-m-d', $value) ?: null;
    }
}


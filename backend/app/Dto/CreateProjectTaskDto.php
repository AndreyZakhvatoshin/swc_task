<?php

declare(strict_types=1);

namespace App\Dto;

use App\Casts\DateWithoutTimeCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateProjectTaskDto extends Data
{
    public function __construct(
        public string $title,
        public string $description,
        #[WithCast(DateWithoutTimeCast::class)]
        public ?\DateTimeImmutable $completion_date = null,
        public ?array $attachments = null,
        public ?int $user_id = null,
    ) {
    }
}

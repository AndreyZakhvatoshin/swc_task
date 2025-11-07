<?php

declare(strict_types=1);

namespace App\Dto;

use App\Casts\DateWithoutTimeCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class CreateProjectTaskDto extends Data
{
    public function __construct(
        public string $title,
        public string $description,
        public int $user_id,
        public string $status,
        #[WithCast(DateWithoutTimeCast::class)]
        public ?\DateTimeImmutable $completion_date = null,
        public ?array $attachments = null,
    ) {
    }
}

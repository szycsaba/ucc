<?php

namespace App\DTO;

class CreateEventData
{
    public function __construct(
        public readonly int $userId,
        public readonly string $title,
        public readonly string $occursAt,
        public readonly string $description,
    ) {}
}

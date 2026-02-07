<?php

namespace App\Repositories;

use App\DTO\CreateEventData;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function createEvent(CreateEventData $data): Event;
}
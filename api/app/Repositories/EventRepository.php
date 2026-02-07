<?php

namespace App\Repositories;

use App\DTO\CreateEventData;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function createEvent(CreateEventData $data): Event
    {
        $event = new Event();
        $event->user_id = $data->userId;
        $event->title = $data->title;
        $event->occurs_at = $data->occursAt;
        $event->description = $data->description;
        $event->save();

        return Event::with(['user'])
            ->findOrFail($event->id);
    }


}
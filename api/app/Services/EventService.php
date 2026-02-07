<?php

namespace App\Services;

use App\DTO\CreateEventData;
use App\DTO\ServiceResponse;
use App\Http\Resources\EventResource;
use App\Repositories\EventRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class EventService
{
    public function __construct(
        private EventRepositoryInterface $repo
    ) {}

    public function createEvent(array $params): ServiceResponse
    {
        try {
            $user = Auth::user();

            $data = new CreateEventData(
                userId: $user->id,
                title: $params['title'],
                occursAt: $params['occurs_at'],
                description: $params['description'],
            );
            $Event = $this->repo->createEvent($data);

            return new ServiceResponse(
                success: true,
                message: 'Event created successfully',
                data: (new EventResource($Event))->toArray(request()),
                status: 201
            );
        } catch (QueryException $e) {
            Log::error('An error occurred while creating Event: ' . $e->getMessage());
            return new ServiceResponse(
                success: false,
                message: 'An error occurred while creating Event',
                status: 500
            );
        }
    }

}
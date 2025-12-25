<?php

declare(strict_types=1);

namespace App\Actions\Customer\Event;

use Illuminate\Support\Facades\DB;
use App\Repositories\EventRepository;
use App\Http\Resources\Customer\Event\EventResource;

final readonly class GetEventAction
{
    public function __construct(private readonly EventRepository $eventRepository) {}

    /**
     * Execute the action.
     */
    public function execute(string $slug)
    {
        return DB::transaction(function () use ($slug) {
            $event = $this->eventRepository->findBySlug($slug, ['scheduledNotifications']);

            return EventResource::make($event);
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Actions\Customer\Event;

use Illuminate\Support\Facades\DB;
use App\Repositories\EventRepository;

final readonly class GetEventsCityAction
{
    public function __construct(private readonly EventRepository $eventRepository) {}

    public function execute(): array
    {
        return DB::transaction(function () {
            return $this->eventRepository->getEventCities();
        });
    }
}

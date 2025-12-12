<?php

declare(strict_types=1);

namespace App\Actions\Customer\Events;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

final readonly class GetEventsCityAction
{
    /**
     * Execute the action.
     */
    public function execute()
    {
        return DB::transaction(function () {
            return Event::where('st_date', '>=', now()->toDateString())
                ->distinct()
                ->pluck('city');
        });
    }
}

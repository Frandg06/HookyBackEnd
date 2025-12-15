<?php

declare(strict_types=1);

namespace App\Actions\Customer\Events;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

final readonly class GetActiveEventByCompanyAction
{
    /**
     * Execute the action.
     */
    public function execute(string $companyUid): Event
    {
        return DB::transaction(function () use ($companyUid) {
            return Event::where('company_uid', $companyUid)
                ->where('st_date', '<=', now())
                ->where('end_date', '>=', now())
                ->firstOrFail();
        });
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hoursForRecentEntries = [];

        for ($i = 9; $i >= 0; $i--) {
            $hoursForRecentEntries[] = now()->subHours($i)->startOfHour()->format('H:i');
        }

        $hourMap = [];
        foreach ($this->recent_entries as $entry) {
            $hourMap[$entry['hour']] = $entry['count'];
        }

        // Construir el array completo con las horas faltantes
        $recent_entries = [];
        foreach ($hoursForRecentEntries as $hour) {
            $recent_entries[] = [
                'hour' => $hour,
                'count' => $hourMap[$hour] ?? 0
            ];
        }

        return [
            'uid' => $this->uid,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'average_ticket_price' => $this->average_ticket_price,
            'timezone_uid' => $this->timezone_uid,
            'timezone_string' => $this->timezone->name,
            'total_tickets' => $this->total_tickets,
            'total_users' => $this->total_users,
            'qr_url' => config('filesystems.disks.r2.url') . 'qr/' . $this->uid . '.png',
            'link' => $this->link,
            'users_incomes' => $this->lastSevenEvents,
            'recent_entries_count' => $recent_entries,
            'last_event' => $this->last_event ? [
                'total_users' => $this->last_event->total_users,
                'incomes' => $this->last_event->total_incomes,
                'hooks' => $this->last_event->hooks,
                'tickets' => $this->last_event->tickets()->count(),
                'avg_age' => round($this->last_event->avg_age),
                'percents' => $this->last_event->percents,
                'name' => $this->last_event->name,
                'date' => $this->last_event->st_date
            ] : null,
        ];
    }
}

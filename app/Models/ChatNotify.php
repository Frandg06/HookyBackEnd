<?php


namespace App\Models;

use Illuminate\Support\Facades\Http;

class ChatNotify extends Notifify
{

    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
    }

    public function emit()
    {
        $url = config('services.ws_api.send_message');

        Http::withHeaders([
          'Authorization' => 'Bearer ' . request()->bearerToken(),
          'Accept' => 'application/json'
        ])->post($url, $this->toArray());
    }
}

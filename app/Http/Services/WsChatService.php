<?php
namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WsChatService
{
    public function __construct()
    {
        
    }

    public function storeChat($user1_uid, $user2_uid, $event_uid) {

        $user1 = User::where('uid', $user1_uid)->first();
        $user2 = User::where('uid', $user2_uid)->first();
        
        $data = [
            'user1' => [
                'uid' => $user1_uid,
                'name' => $user1->name,
                'avatar' => $user1->userImages()->first()->web_url,
            ],
            'user2' => [
                'uid' => $user2_uid,
                'name' => $user2->name,
                'avatar' => $user2->userImages()->first()->web_url,
            ],
            'event_uid' => $event_uid,
        ];

        try {
            $response = Http::withHeaders([
              'Authorization' => 'Bearer '.request()->bearerToken(),
              'Accept' => 'application/json'
            ])->post('http://localhost:5001/api/ws/chat', $data);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        
    }

}
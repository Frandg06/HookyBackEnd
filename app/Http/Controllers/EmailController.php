<?php

namespace App\Http\Controllers;

use App\Models\WaitlistEmail;
use Illuminate\Http\Request;
use SendGrid;
use SendGrid\Mail\Mail;

class EmailController extends Controller
{

    public function storeWaitlist(Request $request)
    {
        $email = $request->email;

        if (!$email) {
            return response()->json(['message' => 'El email es obligatorio', 'error' => true], 400);
        }

        WaitlistEmail::create([
            'email' => $email
        ]);

        return response()->json(['success' => true, 'message' => 'Se ha incluido el email a la lista de espera']);
    }
}

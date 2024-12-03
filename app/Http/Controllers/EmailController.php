<?php

namespace App\Http\Controllers;

use App\Models\WaitlistEmail;
use Illuminate\Http\Request;
use SendGrid;
use SendGrid\Mail\Mail;

class EmailController extends Controller
{
    public function test(Request $request)
    {
        $email = new Mail(); 
        $email->setFrom("admin@hookyapp.es", "Hooky!"); 
        $email->setSubject("Sending with SendGrid is Fun");
        $email->addTo("fdiez86@gmail.com", "Example User");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
        );

        $sender = new SendGrid(env('SENDGRID_API_KEY'));
        try {
            $response = $sender->send($email);

            return response()->json(['message' =>  $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeWaitlist(Request $request)  {
        $email = $request->email;

        if(!$email) return response()->json(['message' => 'El email es obligatorio', "error" => true], 400);

        WaitlistEmail::create([
            "email" => $email
        ]);

        return response()->json(['success' => true, "message" => "Se ha incluido el email a la lista de espera"]);
    }
}

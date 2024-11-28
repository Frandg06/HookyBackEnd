<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SendGrid;
use SendGrid\Mail\Mail;

class EmailController extends Controller
{
    public function test(Request $request)
    {
        $email = new Mail(); 
        $email->setFrom("admin@hooky.com", "Hooky!"); 
        $email->setSubject("Sending with SendGrid is Fun");
        $email->addTo("test@example.com", "Example User");
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
}

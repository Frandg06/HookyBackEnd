<?php

declare(strict_types=1);

namespace App\Http\Services;

use Exception;
use SendGrid;
use SendGrid\Mail\Mail;

final class EmailService
{
    private $sender;

    public function __construct()
    {
        $this->sender = new SendGrid(env('SENDGRID_API_KEY'));
    }

    public function sendEmail($user, $subject, $template)
    {

        $email = new Mail;

        $email->setFrom('admin@hookyapp.es', 'Hooky!');
        $email->setSubject($subject);
        $email->addTo($user->email, $user->name);
        $email->addContent(
            'text/html',
            $template
        );

        try {
            $response = $this->sender->send($email);

            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

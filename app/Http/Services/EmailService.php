<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Exception;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail as FacadesMail;
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

    public function sendPasswordResetEmail(User $user, string $token): SentMessage
    {
        return FacadesMail::to($user->email)->send(new PasswordResetMail($token, $user->name));
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Exception;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail as FacadesMail;

final class EmailService
{
    private $sender;

    public function sendEmail($user, $subject, $template)
    {

        // $email = FacadesMail::to($user->email);

        // $email->setFrom('admin@hookyapp.es', 'Hooky!');
        // $email->setSubject($subject);
        // $email->addTo($user->email, $user->name);
        // $email->addContent(
        //     'text/html',
        //     $template
        // );

        // try {
        //     $response = $this->sender->send($email);

        //     return $response;
        // } catch (Exception $e) {
        //     return $e->getMessage();
        // }
    }

    public function sendPasswordResetEmail(User $user, string $token): SentMessage
    {
        return FacadesMail::to($user->email)->send(new PasswordResetMail($token, $user->name));
    }
}

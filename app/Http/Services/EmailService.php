<?php

declare(strict_types=1);

namespace App\Http\Services;

use Exception;
use App\Models\User;
use App\Models\Event;
use App\Mail\PasswordResetMail;
use Illuminate\Mail\SentMessage;
use App\Mail\NotifyStarOfEventMail;
use Illuminate\Support\Facades\Mail as FacadesMail;

final class EmailService
{
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

    public function sendPasswordResetEmail(User $user, string $token)
    {
        return FacadesMail::to($user->email)->queue(new PasswordResetMail($token, $user->name));
    }

    public function sendNotifyStartOfEventEmail(User $user, Event $event): SentMessage
    {
        return FacadesMail::to($user->email)->send(new NotifyStarOfEventMail($user, $event));
    }
}

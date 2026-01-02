<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use App\Models\Event;
use App\Mail\PasswordResetMail;
use App\Mail\PaymentSuccessMail;
use Illuminate\Mail\SentMessage;
use App\Mail\NotifyStarOfEventMail;
use Illuminate\Support\Facades\Mail as FacadesMail;

final class EmailService
{
    public function sendPasswordResetEmail(User $user, string $token)
    {
        return FacadesMail::to($user->email)->queue(new PasswordResetMail($token, $user->name));
    }

    public function sendNotifyStartOfEventEmail(User $user, Event $event): SentMessage
    {
        return FacadesMail::to($user->email)->send(new NotifyStarOfEventMail($user, $event));
    }

    public function sendPaymentSuccessEmail(User $user, array $paymentDetails): void
    {
        FacadesMail::to($user->email)->queue(new PaymentSuccessMail($paymentDetails));
    }
}

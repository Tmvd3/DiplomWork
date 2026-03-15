<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailTwoFactorCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $code,
        public int $expiresInMinutes = 10,
        public string $intent = 'login',
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isRegistrationFlow = $this->intent === 'register';

        return (new MailMessage)
            ->subject($isRegistrationFlow ? 'Registration verification code' : 'Login verification code')
            ->view('emails.two-factor-code', [
                'code' => $this->code,
                'expiresInMinutes' => $this->expiresInMinutes,
                'appName' => config('app.name', 'Car Diary'),
                'intent' => $this->intent,
            ]);
    }
}

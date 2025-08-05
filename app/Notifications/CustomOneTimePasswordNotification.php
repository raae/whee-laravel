<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Support\Facades\Log;
use Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification;

class CustomOneTimePasswordNotification extends OneTimePasswordNotification
{
    public function via($notifiable): string|array
    {
        return config('one-time-passwords.notification_channels', []);
    }

    // Email notification

    public function toMail(object $notifiable): MailMessage
    {
        // $this->oneTimePassword is an instance of the Spatie\OneTimePasswords\OneTimePassword model

        Log::info('Sending email notification to '.$notifiable->email);

        return (new MailMessage)
            ->subject('Your One-Time Login Code - WheeBike')
            ->greeting('Hello!')
            ->line('You have requested a one-time login code for your WheeBike account.')
            ->line("Your verification code is: **{$this->oneTimePassword->password}**")
            ->line('This code will expire in '.config('one-time-passwords.default_expires_in_minutes').' minutes.')
            ->line('If you did not request this code, please ignore this email.')
            ->salutation('Best regards, WheeBike Team');
    }

    // SMS notification

    public function toVonage(object $notifiable): VonageMessage
    {
        Log::info('Sending SMS notification to '.$notifiable->phone);

        return (new VonageMessage)
            ->content("Your one-time login code is: {$this->oneTimePassword->password}");
    }
}

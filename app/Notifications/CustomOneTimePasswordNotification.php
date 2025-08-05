<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Support\Facades\Log;
use Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification;
use InvalidArgumentException;

class CustomOneTimePasswordNotification extends OneTimePasswordNotification
{
    public function via($notifiable): string|array
    {
        $channels = config('one-time-passwords.notification_channels', []);

        // Validate that channels are configured
        if (empty($channels)) {
            $error = 'No notification channels configured for OTP. Please set OTP_NOTIFICATION_CHANNELS in your .env file (e.g., OTP_NOTIFICATION_CHANNELS=mail,vonage)';

            Log::error($error, [
                'notifiable_id' => $notifiable->id ?? null,
                'notifiable_type' => get_class($notifiable),
                'env_value' => env('OTP_NOTIFICATION_CHANNELS'),
            ]);

            // Throw exception to prevent silent failures
            throw new InvalidArgumentException($error);
        }

        // Validate that configured channels are supported
        $supportedChannels = ['mail', 'vonage'];
        $invalidChannels = array_diff($channels, $supportedChannels);

        if (!empty($invalidChannels)) {
            $error = 'Invalid notification channels configured: ' . implode(', ', $invalidChannels) .
                    '. Supported channels: ' . implode(', ', $supportedChannels);

            Log::error($error, [
                'configured_channels' => $channels,
                'invalid_channels' => $invalidChannels,
                'supported_channels' => $supportedChannels,
            ]);

            throw new InvalidArgumentException($error);
        }

        Log::info('Using OTP notification channels: ' . implode(', ', $channels));

        return $channels;
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

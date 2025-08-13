<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set Carbon locale to match application locale
        \Carbon\Carbon::setLocale(config('app.locale'));

        $this->validateOtpNotificationConfig();
    }

    /**
     * Validate OTP notification configuration during application boot
     */
    private function validateOtpNotificationConfig(): void
    {
        // Only validate in non-testing environments to avoid issues during tests
        if (app()->environment('testing')) {
            return;
        }

        $channels = config('one-time-passwords.notification_channels', []);
        $envValue = env('OTP_NOTIFICATION_CHANNELS');

        // Warn about missing configuration
        if (empty($channels)) {
            $message = 'OTP notification channels not configured. Set OTP_NOTIFICATION_CHANNELS in your .env file.';

            Log::warning($message, [
                'suggestion' => 'Add OTP_NOTIFICATION_CHANNELS=mail,vonage to your .env file',
                'current_env_value' => $envValue,
            ]);

            // In development, also output to console for immediate visibility
            if (app()->environment('local')) {
                echo "\n⚠️  WARNING: {$message}\n";
                echo "   Add this to your .env file: OTP_NOTIFICATION_CHANNELS=mail,vonage\n\n";
            }
        }

        // Validate supported channels
        if (!empty($channels)) {
            $supportedChannels = ['mail', 'vonage'];
            $invalidChannels = array_diff($channels, $supportedChannels);

            if (!empty($invalidChannels)) {
                $message = 'Invalid OTP notification channels configured: ' . implode(', ', $invalidChannels);

                Log::error($message, [
                    'configured_channels' => $channels,
                    'invalid_channels' => $invalidChannels,
                    'supported_channels' => $supportedChannels,
                ]);

                if (app()->environment('local')) {
                    echo "\n❌ ERROR: {$message}\n";
                    echo "   Supported channels: " . implode(', ', $supportedChannels) . "\n\n";
                }
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\OneTimePasswords\Enums\ConsumeOneTimePasswordResult;

class AuthController extends Controller
{

    /**
     * Send OTP to the user
     */
    public function sendOtp(Request $request)
    {

        // Invalidate the session to start fresh
        $request->session()->invalidate();

        $request->validate([
            'phone' => 'required|phone:NO',
        ], [
            'phone.required' => __('auth.phone.required'),
            'phone.phone' => __('auth.phone.invalid'),
        ]);

        try {
            $user = User::findOrCreateFromAirtable($request->phone);

            if (! $user) {
                return back()->withErrors(['phone' => __('auth.phone.not_registered')])->withInput();
            }

            $user->sendOneTimePassword();

            Log::info('OTP sent to ' . $user->phone);

            $request->session()->put('phone_for_verification', $request->phone);

            return redirect()->route('verify-otp');

        } catch (\Exception $e) {
            return back()->withErrors(['phone' => 'Failed to send OTP: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Log in using the OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

                try {
            // Find the user by the phone number that was used to send the OTP
            $phone = $request->session()->get('phone_for_verification');
            $user = User::where('phone', $phone)->firstOrFail();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['phone' => __('auth.phone.not_registered')]);
        }

        $result = $user->attemptLoginUsingOneTimePassword($request->otp, remember: true);

        if ($result->isOk()) {
            Log::info('OTP verified for ' . $user->phone);
            $user->phone_verified_at = now();
            $user->save();
            // It is best practice to regenerate the session id after a login
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'otp' => $this->getOtpTranslationMessage($result),
        ])->onlyInput('otp');
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Get translated OTP validation message based on the result
     */
    private function getOtpTranslationMessage(ConsumeOneTimePasswordResult $result): string
    {
        return match ($result) {
            ConsumeOneTimePasswordResult::Ok => __('auth.otp_success.verified'),
            ConsumeOneTimePasswordResult::NoOneTimePasswordsFound => __('auth.otp_errors.no_passwords_found'),
            ConsumeOneTimePasswordResult::IncorrectOneTimePassword => __('auth.otp_errors.incorrect'),
            ConsumeOneTimePasswordResult::DifferentOrigin => __('auth.otp_errors.different_origin'),
            ConsumeOneTimePasswordResult::OneTimePasswordExpired => __('auth.otp_errors.expired'),
            ConsumeOneTimePasswordResult::RateLimitExceeded => __('auth.otp_errors.rate_limit_exceeded'),
        };
    }
}

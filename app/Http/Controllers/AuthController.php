<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AirtableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\OneTimePasswords\Enums\ConsumeOneTimePasswordResult;

class AuthController extends Controller
{
    protected $airtableService;

    public function __construct(AirtableService $airtableService)
    {
        $this->airtableService = $airtableService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|phone:NO',
        ], [
            'phone.required' => __('auth.phone.required'),
            'phone.phone' => __('auth.phone.invalid'),
        ]);

        try {
            // Fetch user data from Airtable by phone
            $airtableUser = $this->airtableService->getUserByPhone($request->phone);

            if (! $airtableUser) {
                return back()->withErrors(['phone' => __('auth.phone.not_registered')])->withInput();
            }

            // Create or update local user record
            $user = User::createFromAirtable($airtableUser);

            // Generate and send OTP via Email (but lookup was by phone)
            $otp = $user->sendOneTimePassword();

            Log::info('OTP sent to ' . $request->phone);
            Log::info('OTP: ' . $otp->expires_at);

            // Store phone number in session for verification step
            $request->session()->put('phone_for_verification', $request->phone);

            // Handle JSON response for API calls
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'OTP sent successfully',
                    'expires_at' => $otp->expires_at,
                ]);
            }

            // Handle form submission - redirect to verification page
            return redirect()->route('verify-otp');

        } catch (\Exception $e) {
            return back()->withErrors(['phone' => 'Failed to send OTP: '.$e->getMessage()])->withInput();
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        // Get phone from session or request
        $phone = $request->phone ?? $request->session()->get('phone_for_verification');

        if (! $phone) {
            return redirect()->route('login')->withErrors(['phone' => __('auth.otp_errors.default')]);
        }

        // Find user by phone
        $user = User::where('phone', $phone)->first();

        if (! $user) {
            return redirect()->route('login')->withErrors(['phone' => __('auth.phone.not_registered')]);
        }

        // Attempt login using one-time password
        $result = $user->attemptLoginUsingOneTimePassword($request->otp, remember: false);

        if ($result->isOk()) {
            // It is best practice to regenerate the session id after a login
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'otp' => $this->getOtpTranslationMessage($result),
        ])->onlyInput('otp');
    }

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

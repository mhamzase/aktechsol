<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

/**
 * AJAX two-factor authentication management (Fortify confirm mode).
 * States: disabled → confirming (QR + code) → enabled (recovery codes).
 */
class TwoFactorController extends Controller
{
    /**
     * Current 2FA state for the logged-in user.
     */
    public function status()
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return response()->json([
                'state' => 'enabled',
                'recovery_codes' => $user->recoveryCodes(),
            ]);
        }

        // Secret exists but never confirmed (e.g. abandoned setup) → resume it.
        if (! empty($user->two_factor_secret)) {
            return response()->json([
                'state' => 'confirming',
                'qr_svg' => $user->twoFactorQrCodeSvg(),
                'secret' => decrypt($user->two_factor_secret),
            ]);
        }

        return response()->json(['state' => 'disabled']);
    }

    /**
     * Start setup: generate a fresh secret and return the QR code.
     */
    public function enable()
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return response()->json(['message' => 'Two-factor authentication is already enabled.'], 422);
        }

        app(EnableTwoFactorAuthentication::class)($user, true);
        $user->refresh();

        return response()->json([
            'success' => true,
            'qr_svg' => $user->twoFactorQrCodeSvg(),
            'secret' => decrypt($user->two_factor_secret),
            'message' => 'Scan the QR code with your authenticator app, then confirm with a code.',
        ]);
    }

    /**
     * Verify the 6-digit code and fully enable 2FA.
     */
    public function confirm(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);
        $user = auth()->user();

        $confirmed = app(ConfirmTwoFactorAuthentication::class)($user, $request->input('code'));

        if (! $confirmed) {
            return response()->json(['message' => 'Invalid code. Please try again.'], 422);
        }

        return response()->json([
            'success' => true,
            'recovery_codes' => $user->recoveryCodes(),
            'message' => 'Two-factor authentication enabled.',
        ]);
    }

    /**
     * Disable 2FA entirely (clears secret + recovery codes).
     */
    public function disable()
    {
        $user = auth()->user();
        app(DisableTwoFactorAuthentication::class)($user);

        return response()->json([
            'success' => true,
            'message' => 'Two-factor authentication disabled.',
        ]);
    }

    /**
     * Generate a fresh set of recovery codes.
     */
    public function regenerateCodes()
    {
        $user = auth()->user();

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            return response()->json(['message' => 'Two-factor authentication is not enabled.'], 422);
        }

        app(GenerateNewRecoveryCodes::class)($user);
        $user->refresh();

        return response()->json([
            'success' => true,
            'recovery_codes' => $user->recoveryCodes(),
            'message' => 'New recovery codes generated.',
        ]);
    }
}

<?php

namespace App\Services\Security;

use App\Models\User\User;
use PragmaRX\Google2FALaravel\Google2FA;

class TwoFactorAuthService
{
    public function __construct(
        private readonly Google2FA $google2fa
    ) {}

    /**
     * Generate secret key
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR code for Google Authenticator
     */
    public function getQrCodeUrl(User $user, string $secret): string
    {
        $companyName = config('app.name', 'Moroccanmeraki.com');

        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $user->email,
            $secret
        );
    }

    /**
     * Generate QR code markup
     */
    public function getQrCodeSvg(User $user, string $secret): string
    {
        $qrCode = $this->google2fa->getQRCodeInline(
            config('app.name', 'Moroccanmeraki.com'),
            $user->email,
            $secret,
            200
        );

        $qrCode = trim($qrCode);

        // google2fa-laravel may return a data URI, raw SVG/XML, or binary image.
        if (str_starts_with($qrCode, 'data:image/')) {
            $safeSrc = htmlspecialchars($qrCode, ENT_QUOTES, 'UTF-8');

            return '<img src="'.$safeSrc.'" alt="QR Code 2FA" class="w-50 h-50" />';
        }

        if (str_contains($qrCode, '<svg')) {
            $svgStart = strpos($qrCode, '<svg');
            $svg = $svgStart === false ? $qrCode : substr($qrCode, $svgStart);
            $src = 'data:image/svg+xml;base64,'.base64_encode($svg);
            $safeSrc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');

            return '<img src="'.$safeSrc.'" alt="QR Code 2FA" class="w-[200px] h-[200px]" />';
        }

        $src = 'data:image/png;base64,'.base64_encode($qrCode);
        $safeSrc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');

        return '<img src="'.$safeSrc.'" alt="QR Code 2FA" class="w-[200px] h-[200px]" />';
    }

    /**
     * Verify code
     */
    public function verify(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Enable 2FA for user
     */
    public function enable(User $user, string $code): bool
    {
        if (! $user->two_factor_secret) {
            return false;
        }

        if (! $this->verify($user->two_factor_secret, $code)) {
            return false;
        }

        $user->update([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $user->generateRecoveryCodes(),
        ]);

        return true;
    }

    /**
     * Disable 2FA for user
     */
    public function disable(User $user): void
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(User $user): array
    {
        $codes = $user->generateRecoveryCodes();

        $user->update([
            'two_factor_recovery_codes' => $codes,
        ]);

        return $codes;
    }

    /**
     * Use a recovery code
     */
    public function useRecoveryCode(User $user, string $code): bool
    {
        $codes = $user->recovery_codes;

        if (! $codes || ! in_array($code, $codes)) {
            return false;
        }

        // Remove used code
        $codes = array_values(array_diff($codes, [$code]));

        $user->update([
            'two_factor_recovery_codes' => $codes ?: null,
        ]);

        return true;
    }
}

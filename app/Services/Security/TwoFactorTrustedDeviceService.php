<?php

namespace App\Services\Security;

use App\Models\Security\TwoFactorTrustedDevice;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class TwoFactorTrustedDeviceService
{
    public const COOKIE_NAME = 'moroccanmeraki_two_factor_trusted_device';

    public function isTrusted(Model&Authenticatable $authenticatable, Request $request): bool
    {
        $token = $request->cookie(self::COOKIE_NAME);

        if (! is_string($token) || $token === '') {
            return false;
        }

        $device = $this->findTrustedDevice($authenticatable, $token);

        if (! $device instanceof TwoFactorTrustedDevice) {
            return false;
        }

        $device->update(['last_used_at' => now()]);

        return true;
    }

    /** @return Collection<int, TwoFactorTrustedDevice> */
    public function devicesFor(Model&Authenticatable $authenticatable): Collection
    {
        return TwoFactorTrustedDevice::query()
            ->where('authenticatable_type', $authenticatable->getMorphClass())
            ->where('authenticatable_id', $authenticatable->getAuthIdentifier())
            ->where('expires_at', '>', now())
            ->latest('last_used_at')
            ->get();
    }

    public function isCurrent(TwoFactorTrustedDevice $device, Request $request): bool
    {
        $token = $request->cookie(self::COOKIE_NAME);

        return is_string($token)
            && $token !== ''
            && hash_equals($device->token_hash, $this->hashToken($token));
    }

    public function remember(Model&Authenticatable $authenticatable, Request $request): Cookie
    {
        $token = bin2hex(random_bytes(32));
        $minutes = $this->trustedDeviceDays() * 1440;

        TwoFactorTrustedDevice::query()->create([
            'authenticatable_type' => $authenticatable->getMorphClass(),
            'authenticatable_id' => $authenticatable->getAuthIdentifier(),
            'token_hash' => $this->hashToken($token),
            'expires_at' => now()->addDays($this->trustedDeviceDays()),
            'last_used_at' => now(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 512),
            'ip_address' => $request->ip(),
        ]);

        return cookie()->make(
            self::COOKIE_NAME,
            $token,
            $minutes,
            '/',
            null,
            (bool) config('session.secure'),
            true,
            false,
            config('session.same_site', 'lax'),
        );
    }

    public function revokeAll(Model&Authenticatable $authenticatable): void
    {
        TwoFactorTrustedDevice::query()
            ->where('authenticatable_type', $authenticatable->getMorphClass())
            ->where('authenticatable_id', $authenticatable->getAuthIdentifier())
            ->delete();
    }

    public function revoke(Model&Authenticatable $authenticatable, TwoFactorTrustedDevice $device): bool
    {
        if (
            $device->authenticatable_type !== $authenticatable->getMorphClass()
            || (int) $device->authenticatable_id !== (int) $authenticatable->getAuthIdentifier()
        ) {
            return false;
        }

        return (bool) $device->delete();
    }

    private function findTrustedDevice(Model&Authenticatable $authenticatable, string $token): ?TwoFactorTrustedDevice
    {
        return TwoFactorTrustedDevice::query()
            ->where('authenticatable_type', $authenticatable->getMorphClass())
            ->where('authenticatable_id', $authenticatable->getAuthIdentifier())
            ->where('token_hash', $this->hashToken($token))
            ->where('expires_at', '>', now())
            ->first();
    }

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    private function trustedDeviceDays(): int
    {
        return max(1, (int) config('auth.customer_two_factor.trusted_device_days', 90));
    }
}

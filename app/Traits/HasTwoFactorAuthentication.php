<?php

namespace App\Traits;

trait HasTwoFactorAuthentication
{
    public function hasTwoFactorEnabled(): bool
    {
        return $this->getRawOriginal('two_factor_secret') !== null
            && $this->getRawOriginal('two_factor_confirmed_at') !== null;
    }

    public function getTwoFactorRecoveryCodesAttribute(mixed $value): ?array
    {
        if ($value === null || $value === '') {
            return null;
        }

        return json_decode(decrypt($value), true);
    }

    public function setTwoFactorRecoveryCodesAttribute(?array $value): void
    {
        $this->attributes['two_factor_recovery_codes'] = $value === null
            ? null
            : encrypt(json_encode($value));
    }

    public function getRecoveryCodesAttribute(): ?array
    {
        return $this->getTwoFactorRecoveryCodesAttribute(
            $this->getRawOriginal('two_factor_recovery_codes'),
        );
    }

    /** @return array<int, string> */
    public function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn (): string => strtoupper(str()->random(10)))
            ->all();
    }
}

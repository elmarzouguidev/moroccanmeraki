<?php

namespace App\Helpers;

use App\Models\Utilities\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityHelper
{
    /**
     * Log a custom activity
     */
    public static function log(
        string $description,
        ?Model $subject = null,
        string $logName = 'default',
        array $properties = [],
        string $event = 'custom'
    ): ActivityLog {
        $request = self::currentRequest();
        $properties = array_merge(
            self::requestContextProperties($request),
            $properties
        );

        return ActivityLog::create([
            'log_name' => $logName,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'causer_type' => auth()->check() ? get_class(auth()->user()) : null,
            'causer_id' => auth()->id(),
            'properties' => $properties,
            'event' => $event,
            'ip_address' => $request ? getRealIp($request) : null,
            'user_agent' => $request?->userAgent(),
        ]);
    }

    /**
     * Log authentication events
     */
    public static function logAuth(string $event, ?Model $user = null, array $properties = []): ActivityLog
    {
        $descriptions = [
            'login' => 'Connexion réussie',
            'logout' => 'Déconnexion',
            'login_failed' => 'Tentative de connexion échouée',
            'password_reset' => 'Mot de passe réinitialisé',
            'email_verified' => 'Email vérifié',
            'login_2fa_app' => 'Connexion réussie avec application d’authentification',
            'login_2fa_email' => 'Connexion réussie avec code email',
            'login_2fa_recovery_code' => 'Connexion réussie avec code de récupération',
        ];

        return self::log(
            $descriptions[$event] ?? $event,
            $user,
            'authentication',
            $properties,
            $event
        );
    }

    private static function currentRequest(): ?Request
    {
        return app()->bound('request') ? request() : null;
    }

    private static function resolveSource(?Request $request): string
    {
        if (!$request) {
            return 'system';
        }

        return $request->is('api/*') ? 'api' : 'web';
    }

    private static function requestContextProperties(?Request $request): array
    {
        if (!$request) {
            return [
                'source' => 'system',
            ];
        }

        $properties = [
            'source' => self::resolveSource($request),
            'method' => strtoupper((string) $request->method()),
            'route' => ltrim((string) $request->path(), '/'),
        ];

        $deviceName = $request->input('device_name')
            ?: $request->header('X-Device-Name')
            ?: $request->header('X-Device-Id')
            ?: $request->user()?->currentAccessToken()?->name;

        if (!empty($deviceName)) {
            $properties['device_name'] = (string) $deviceName;
        }

        return array_filter($properties, fn ($value) => $value !== null && $value !== '');
    }
}

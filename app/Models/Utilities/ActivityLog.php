<?php

namespace App\Models\Utilities;

use App\Traits\GetModelByKeyName;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByKeyName;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * Get the subject of the activity
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who caused the activity
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the changes from properties
     */
    public function getChangesAttribute()
    {
        return $this->properties['attributes'] ?? [];
    }

    /**
     * Get the old values from properties
     */
    public function getOldValuesAttribute()
    {
        return $this->properties['old'] ?? [];
    }

    public function getSourceAttribute(): string
    {
        return strtolower((string) ($this->properties['source'] ?? 'system'));
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'api' => 'Mobile API',
            'web' => 'Web',
            default => 'Système',
        };
    }

    public function getSourceColorAttribute(): string
    {
        return match ($this->source) {
            'api' => 'text-cyan-700 bg-cyan-100',
            'web' => 'text-indigo-700 bg-indigo-100',
            default => 'text-slate-700 bg-slate-100',
        };
    }

    public function getMethodAttribute(): ?string
    {
        $method = $this->properties['method'] ?? null;

        return $method ? strtoupper((string) $method) : null;
    }

    public function getRoutePathAttribute(): ?string
    {
        $route = $this->properties['route'] ?? null;

        return $route ? (string) $route : null;
    }

    public function getDeviceNameAttribute(): ?string
    {
        $deviceName = $this->properties['device_name'] ?? null;

        return $deviceName ? (string) $deviceName : null;
    }

    /**
     * Get icon based on event type
     */
    public function getIconAttribute(): string
    {
        return match ($this->event) {
            'created' => 'M12 4v16m8-8H4',
            'updated' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'deleted' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
            'login' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
            'logout' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        };
    }

    /**
     * Get color based on event type
     */
    public function getColorAttribute(): string
    {
        return match ($this->event) {
            'created' => 'text-green-600 bg-green-100',
            'updated' => 'text-blue-600 bg-blue-100',
            'deleted' => 'text-red-600 bg-red-100',
            'login' => 'text-purple-600 bg-purple-100',
            'logout' => 'text-orange-600 bg-orange-100',
            default => 'text-slate-600 bg-slate-100',
        };
    }

    /**
     * Scope to filter by log name
     */
    public function scopeOfLogName($query, $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope to filter by causer
     */
    public function scopeCausedBy($query, $causer)
    {
        return $query->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->id);
    }

    /**
     * Scope to filter by subject
     */
    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id);
    }
}

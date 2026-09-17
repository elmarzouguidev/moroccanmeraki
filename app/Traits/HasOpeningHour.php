<?php

namespace App\Traits;

use App\Models\Utilities\OpeningHour;
use App\Models\Utilities\OpeningHourException;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasOpeningHour
{
    public function openingHours(): MorphMany
    {
        return $this->morphMany(OpeningHour::class, 'openingable')
            ->orderBy('day_of_week')
            ->orderBy('opens_at');
    }

    public function openingHourExceptions(): MorphMany
    {
        return $this->morphMany(OpeningHourException::class, 'openingable')
            ->where('is_active', true)
            ->where('is_valid', true)
            ->orderBy('exception_date');
    }

    public function openingHoursForDate(CarbonInterface $dateTime): Collection
    {
        $exceptions = $this->relationLoaded('openingHourExceptions')
            ? $this->openingHourExceptions
                ->filter(fn (OpeningHourException $exception): bool => $exception->exception_date?->toDateString() === $dateTime->toDateString())
            : $this->openingHourExceptions()
                ->whereDate('exception_date', $dateTime->toDateString())
                ->get();

        if ($exceptions->isNotEmpty()) {
            return $exceptions->values();
        }

        return $this->openingHours
            ->where('day_of_week', $dateTime->dayOfWeekIso)
            ->values();
    }

    public function isOpenAt(CarbonInterface $dateTime): bool
    {
        return $this->openingHoursForDate($dateTime)
            ->contains(fn (OpeningHour|OpeningHourException $openingHour): bool => $openingHour->isOpenAt($dateTime));
    }

    /**
     * @param  array<int|string, array<string, mixed>>  $openingHours
     */
    public function syncOpeningHours(array $openingHours): void
    {
        $this->openingHours()->withTrashed()->forceDelete();

        foreach ($openingHours as $openingHour) {
            $isClosed = filter_var($openingHour['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $opensAt = $openingHour['opens_at'] ?? null;
            $closesAt = $openingHour['closes_at'] ?? null;

            if (! $isClosed && blank($opensAt) && blank($closesAt)) {
                continue;
            }

            $this->openingHours()->create([
                'day_of_week' => (int) $openingHour['day_of_week'],
                'opens_at' => $isClosed ? null : $opensAt,
                'closes_at' => $isClosed ? null : $closesAt,
                'is_closed' => $isClosed,
                'is_active' => true,
                'is_valid' => true,
            ]);
        }
    }

    /**
     * @param  array<int|string, array<string, mixed>>  $exceptions
     */
    public function syncOpeningHourExceptions(array $exceptions): void
    {
        $this->openingHourExceptions()->withTrashed()->forceDelete();

        foreach ($exceptions as $exception) {
            $exceptionDate = $exception['exception_date'] ?? null;

            if (blank($exceptionDate)) {
                continue;
            }

            $isClosed = filter_var($exception['is_closed'] ?? false, FILTER_VALIDATE_BOOLEAN);

            $this->openingHourExceptions()->create([
                'exception_date' => $exceptionDate,
                'opens_at' => $isClosed ? null : ($exception['opens_at'] ?? null),
                'closes_at' => $isClosed ? null : ($exception['closes_at'] ?? null),
                'is_closed' => $isClosed,
                'reason' => $exception['reason'] ?? null,
                'is_active' => true,
                'is_valid' => true,
            ]);
        }
    }
}

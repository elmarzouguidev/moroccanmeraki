<?php

namespace App\Services\Currency;

use App\Enums\Utilities\ConversionCurrencyType;
use App\Models\Tools\ExchangeRate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CurrencyConversionService
{
    protected ?string $apiKey;
    protected string $primaryApiUrl = 'https://v6.exchangerate-api.com/v6';

    public function __construct()
    {
        $this->apiKey = config('services.exchange_rate.api_key');
    }

    public function convert(
        int|float $amount,
        ConversionCurrencyType $from,
        ConversionCurrencyType $to
    ): float {
        if ($from === $to) {
            return (float) $amount;
        }

        $rate = $this->getExchangeRate($from, $to);
        return round($amount * $rate, $to->getDecimals());
    }

    public function getExchangeRate(ConversionCurrencyType $from, ConversionCurrencyType $to): float
    {
        $cacheKey = "exchange_rate_{$from->value}_{$to->value}";

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($from, $to) {
            $rate = ExchangeRate::where('from_currency', $from->value)
                ->where('to_currency', $to->value)
                ->where('expires_at', '>', now())
                ->first();

            if ($rate) {
                return (float) $rate->rate;
            }

            return $this->fetchRateFromApi($from, $to);
        });
    }

    protected function fetchRateFromApi(ConversionCurrencyType $from, ConversionCurrencyType $to): float
    {
        // 1. Try ExchangeRate-API (Best for MAD)
        if ($this->apiKey) {
            $rate = $this->tryExchangeRateApi($from, $to);
            if ($rate !== null) return $rate;
        }

        // 2. Try ExchangeRate.host (Supports MAD, but has a 100 req/mo free limit)
        $rate = $this->tryExchangeRateHost($from, $to);
        if ($rate !== null) return $rate;

        // 3. Try Frankfurter (Note: This WILL fail for MAD, which is fine as it falls through)
        $rate = $this->tryFrankfurter($from, $to);
        if ($rate !== null) return $rate;

        // 4. Final Fallback: Database
        return $this->getFallbackRate($from, $to);
    }

    protected function tryExchangeRateApi(ConversionCurrencyType $from, ConversionCurrencyType $to): ?float
    {
        try {
            $url = "{$this->primaryApiUrl}/{$this->apiKey}/pair/{$from->value}/{$to->value}";
            $response = Http::timeout(5)->get($url);

            if ($response->successful() && $response->json('result') === 'success') {
                // Must be singular 'conversion_rate' for the /pair/ endpoint
                $rate = $response->json('conversion_rate');

                if (is_numeric($rate)) {
                    $this->storeRate($from, $to, (float) $rate);
                    return (float) $rate;
                }
            }
        } catch (Exception $e) {
            Log::warning("ExchangeRate-API failed: " . $e->getMessage());
        }
        return null;
    }

    protected function tryFrankfurter(ConversionCurrencyType $from, ConversionCurrencyType $to): ?float
    {
        try {
            $response = Http::timeout(5)->get('https://api.frankfurter.app/latest', [
                'from' => $from->value,
                'to' => $to->value,
            ]);

            if ($response->successful()) {
                $rate = $response->json("rates.{$to->value}");
                if (is_numeric($rate)) {
                    $this->storeRate($from, $to, (float) $rate);
                    return (float) $rate;
                }
            }
        } catch (Exception $e) {
            Log::warning("Frankfurter API failed: " . $e->getMessage());
        }
        return null;
    }

    protected function tryExchangeRateHost(ConversionCurrencyType $from, ConversionCurrencyType $to): ?float
    {
        try {
            $response = Http::timeout(5)->get('https://api.exchangerate.host/latest', [
                'base' => $from->value,
                'symbols' => $to->value,
            ]);

            if ($response->successful() && $response->json('success')) {
                $rate = $response->json("rates.{$to->value}");
                if (is_numeric($rate)) {
                    $this->storeRate($from, $to, (float) $rate);
                    return (float) $rate;
                }
            }
        } catch (Exception $e) {
            Log::warning("ExchangeRate.host failed: " . $e->getMessage());
        }
        return null;
    }

    protected function storeRate(ConversionCurrencyType $from, ConversionCurrencyType $to, float $rate): void
    {
        // Ensure we never store a 0 or negative rate by accident
        if ($rate <= 0) return;

        ExchangeRate::updateOrCreate(
            ['from_currency' => $from->value, 'to_currency' => $to->value],
            ['rate' => $rate, 'expires_at' => now()->addDay()]
        );
    }

    protected function getFallbackRate(ConversionCurrencyType $from, ConversionCurrencyType $to): float
    {
        $storedRate = ExchangeRate::where('from_currency', $from->value)
            ->where('to_currency', $to->value)
            ->latest()
            ->first();

        if ($storedRate) {
            return (float) $storedRate->rate;
        }

        throw new Exception("Critical: No exchange rate available for {$from->value} to {$to->value}");
    }

    /**
     * Get all rates for a base currency.
     * Useful for displaying a "Currency Switcher" or a pricing table.
     */
    public function getAllRates(ConversionCurrencyType $baseCurrency): array
    {
        $rates = [];

        foreach (ConversionCurrencyType::cases() as $currency) {
            // Skip the base currency itself (e.g., USD to USD is always 1)
            if ($currency === $baseCurrency) {
                continue;
            }

            try {
                $rates[$currency->value] = $this->getExchangeRate($baseCurrency, $currency);
            } catch (\Exception $e) {
                Log::warning("Failed to get rate for {$baseCurrency->value} to {$currency->value}: " . $e->getMessage());
            }
        }

        return $rates;
    }
}

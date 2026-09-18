<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExchangeRateService
{
    /**
     * Convert an amount between two currencies.
     */
    public function convert(float $amount, string $from, string $to): ?array
    {
        try {
            return Cache::remember("exchange_rate_convert_{$amount}_{$from}_{$to}", 3600, function () use ($amount, $from, $to) {
                $response = Http::timeout(5)->get('https://api.frankfurter.app/latest', [
                    'from' => $from,
                    'to' => $to,
                    'amount' => $amount,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'result' => $data['rates'][$to] ?? null,
                        'rate' => $data['rates'][$to] / $amount,
                        'date' => $data['date'] ?? now()->format('Y-m-d'),
                    ];
                }
                return null;
            });
        } catch (\Exception $e) {
            // Silently fail
        }

        return null;
    }

    /**
     * Fetch latest exchange rates from Frankfurter API.
     */
    public function fetchRates(string $base, string $targets): array
    {
        try {
            return Cache::remember("exchange_rate_latest_{$base}_{$targets}", 3600, function () use ($base, $targets) {
                $response = Http::timeout(5)->get('https://api.frankfurter.app/latest', [
                    'from' => $base,
                    'to' => $targets,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
                return [];
            });
        } catch (\Exception $e) {
            // Silently fail
        }

        return [];
    }

    /**
     * Fetch historical exchange rates for trend comparison.
     */
    public function fetchHistoricalRates(string $base, string $targets, string $date): array
    {
        try {
            return Cache::remember("exchange_rate_historical_{$date}_{$base}_{$targets}", 86400, function () use ($date, $base, $targets) {
                $response = Http::timeout(5)->get("https://api.frankfurter.app/{$date}", [
                    'from' => $base,
                    'to' => $targets,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
                return [];
            });
        } catch (\Exception $e) {
            // Silently fail
        }

        return [];
    }
}

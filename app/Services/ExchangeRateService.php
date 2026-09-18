<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    /**
     * Convert an amount between two currencies.
     */
    public function convert(float $amount, string $from, string $to): ?array
    {
        try {
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
            $response = Http::timeout(5)->get('https://api.frankfurter.app/latest', [
                'from' => $base,
                'to' => $targets,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
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
            $response = Http::timeout(5)->get("https://api.frankfurter.app/{$date}", [
                'from' => $base,
                'to' => $targets,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return [];
    }
}

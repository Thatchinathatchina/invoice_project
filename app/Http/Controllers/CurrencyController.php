<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(protected ExchangeRateService $exchangeRateService)
    {
    }
    /**
     * Supported currencies with their display info.
     */
    public static array $currencies = [
        'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'flag' => '🇺🇸'],
        'EUR' => ['name' => 'Euro', 'symbol' => '€', 'flag' => '🇪🇺'],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'flag' => '🇬🇧'],
        'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥', 'flag' => '🇯🇵'],
        'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$', 'flag' => '🇦🇺'],
        'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$', 'flag' => '🇨🇦'],
        'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'Fr', 'flag' => '🇨🇭'],
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹', 'flag' => '🇮🇳'],
        'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$', 'flag' => '🇸🇬'],
        'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥', 'flag' => '🇨🇳'],
        'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ', 'flag' => '🇦🇪'],
    ];



    /**
     * Handle currency conversion via AJAX.
     */
    public function convert(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
        ]);

        $data = $this->exchangeRateService->convert(
            $validated['amount'], 
            $validated['from'], 
            $validated['to']
        );

        if ($data) {
            return response()->json([
                'success' => true,
                'result' => $data['result'],
                'rate' => $data['rate'],
                'date' => $data['date'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to convert currency. Please try again.',
        ], 422);
    }
}

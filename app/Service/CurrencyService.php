<?php

namespace App\Service;

class CurrencyService
{
    const RATES = [
        'usd' => [
            'eur' => 0.98
        ]
    ];

    public function convert(float $amount, string $currencyFrom, string $currencyTo): float
    {
        $rate = self::RATES[$currencyFrom][$currencyTo] ?? 0;

        return round($amount * $rate, 2);
    }

    public function createUsername()
    {
        $number = 45;

        $f_name = 'ram';

        $l_name = 'bdr';

        $finalValue = $f_name.$number.$l_name.$number;

        return $finalValue;
    }

    public function multiply()
    {
        $val1 = 10;
        $val2 = 45;

        $finalVal = $val1 * $val2;

        return $finalVal;
    }
}
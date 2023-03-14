<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\CurrencyService;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_convert_usd_to_eur()
    {
        $response = (new CurrencyService())->convert(amount: 100, currencyFrom: 'usd', currencyTo: 'eur');
        $this->assertEquals(98, $response);
    }

    public function test_convert_usd_to_npr_returns_zero()
    {
        $response = (new CurrencyService())->convert(100, 'usd', 'npr');
        $this->assertEquals(0, $response);
    }

    public function test_generate_username()
    {
        $response = (new CurrencyService())->createUsername();

        $this->assertEquals('ram45bdr45', $response);
    }

    public function test_multiplication_two_values()
    {
        // $response = (new CurrencyService())->multiply();
        $response = 45 * 10;

        $this->assertEquals(450, $response);
    }
}

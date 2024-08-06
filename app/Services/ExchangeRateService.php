<?php

namespace App\Services;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\Currency;
use GuzzleHttp\Client;

class ExchangeRateService
{
    private $exchangeRateRepository;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }
    public function findByCode(string $code)
    {
        return Currency::where('code', strtoupper($code))->first();
    }

    public function getApiKey()
    {
        $exchangeRateService = InstanceService::getInstance();
        return $exchangeRateService->getApiKey();

    }

    public function getExchangeRates()
    {
        $api_keys = $this->getApiKey();

        $url = "https://v6.exchangerate-api.com/v6/{$api_keys['erapi']}/latest/USD";

        $client = new Client();
        $response = $client->get($url);

        $data = json_decode($response->getBody(), true);

        if (!isset($data['conversion_rates'])) {
            return response()->json(['error' => 'Missing conversion rates'], 500);
        }

            foreach ($data['conversion_rates'] as $currency_code => $rate_data) {
                $from_currency = $this->findByCode($currency_code);
                $to_currency = $this->findByCode($data['base_code']);

                $rate_data = [
                    'date' => $data['time_last_update_utc'],
                    'rate' => $rate_data,
                    'from_currency_id' => $from_currency,
                    'to_currency_id' => $to_currency,
                ];

//                $rate_data = [
//                    'date' => 'Fri, 27 Mar 2020 00:00:00 +0000',
//                    'rate' => 436,
//                    'from_currency_id' => 12,
//                    'to_currency_id' => 1,
//                ];

                dd($rate_data);
//                $this->exchangeRateRepository->update($rate_data);

            }

            return '';
    }
}

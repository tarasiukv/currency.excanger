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
//        $api_keys = $this->getApiKey();
//
//        $url = "https://v6.exchangerate-api.com/v6/{$api_keys['erapi']}/latest/USD";
//
//        $client = new Client();
//        $response = $client->get($url);
//
//        $data = json_decode($response->getBody(), true);
//
//        if (!isset($data['conversion_rates'])) {
//            return response()->json(['error' => 'Missing conversion rates'], 500);
//        }

        $data = [
            "result" => "success",
            "documentation" => "https://www.exchangerate-api.com/docs",
            "terms_of_use" => "https://www.exchangerate-api.com/terms",
            "time_last_update_unix" => 1722902401,
            "time_last_update_utc" => "Tue, 06 Aug 2024 00:00:01 +0000",
            "time_next_update_unix" => 1722988801,
            "time_next_update_utc" => "Wed, 07 Aug 2024 00:00:01 +0000",
            "base_code" => "USD",
            "conversion_rates" => [
                "USD" => 1,
                "AED" => 3.6725,
                "AFN" => 70.6389,
                "ALL" => 91.5840,
                "AMD" => 387.9398,
                "UAH" => 41.0398,
                "ZWL" => 3.7654
            ]
        ];

        foreach ($data['conversion_rates'] as $currency_code => $rate_data) {
            $from_currency = $this->findByCode($currency_code);
            $to_currency = $this->findByCode($data['base_code']);

            if (isset($from_currency) && isset($to_currency)) {
                $currency_rate_data = [
                    'date' => $data['time_last_update_utc'],
                    'from_currency_id' => $from_currency->id,
                    'to_currency_id' => $to_currency->id,
                    'rate' => $rate_data,
                ];
                $this->exchangeRateRepository->update($currency_rate_data);
            }

            //clear memory
            unset($data);
            gc_collect_cycles();
        }
    }
}

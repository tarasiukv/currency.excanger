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

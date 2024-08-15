<?php

namespace App\Services;

use App\Http\Requests\ExchangeRateRequest;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ExchangeRateService
{
    private $exchangeRateRepository;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function findByCode(string $code)
    {
        return Currency::where('code', strtoupper($code))->first();
    }

    /**
     * @return array
     */
    public function getApiUrl()
    {
        $exchangeRateService = InstanceService::getInstance();
        return $exchangeRateService->getApiUrls();
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getJSONData()
    {
        $api_urls = $this->getApiUrl();

        $client = new Client();
        $response = $client->get($api_urls['erapi_api_url']);

        return json_decode($response->getBody(), true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getExchangeRates()
    {
        $data = $this->getJSONData();

        if (!isset($data['conversion_rates'])) {
            Log::channel('exchangeRate')->error('Missing conversion rates');

            return response()->json(['error' => 'Missing conversion rates'], 500);
        }

        foreach ($data['conversion_rates'] as $currency_code => $rate_data) {
            $from_currency = $this->findByCode($currency_code);
            $to_currency = $this->findByCode($data['base_code']);

            if (!$from_currency) {
                Log::channel('exchangeRate')->warning("Currency not found: from_currency={$currency_code}");
                continue;
            }

            if (!$to_currency) {
                Log::channel('exchangeRate')->warning("Currency not found: to_currency={$data['base_code']}");
                continue;
            }

            $currency_rate_data = [
                'date' => $data['time_last_update_utc'],
                'from_currency_id' => $from_currency->id,
                'to_currency_id' => $to_currency->id,
                'rate' => $rate_data,
            ];

            $validator = Validator::make($currency_rate_data, (new ExchangeRateRequest())->rules());

            try {
                $validated_data = $validator->validate();
                $this->exchangeRateRepository->update($validated_data);
                Log::channel('exchangeRate')->info("Exchange rate updated: from_currency_id={$from_currency->id}, to_currency_id={$to_currency->id}, rate={$rate_data}");

            } catch (ValidationException $e) {
                Log::channel('exchangeRate')->error('Validation failed', ['errors' => $e->errors()]);
                continue;
            }
        }
    }
}

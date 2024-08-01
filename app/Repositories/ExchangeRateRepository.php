<?php

namespace App\Repositories;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PharIo\Version\Exception;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function index()
    {
        return ExchangeRate::with('currency')->get();
    }

    public function update()
    {
        $apiKey = $this->exchangeRateService->getApiKey();

        $url = "https://api.minfin.com.ua/nbu/{$apiKey}/";

        $response = Http::get($url);
        $data = $response->json();

        foreach ($data as $code => $rateData) {
            $currency = $this->exchangeRateService->findByCode($code);

            if ($currency) {
                try {
                    DB::beginTransaction();
                    $exchange_rate = ExchangeRate::updateOrCreate(
                        ['currency_id' => $currency->id],
                        [
                            'ask' => $rateData['ask'],
                            'bid' => $rateData['bid']
                        ]
                    );

                    $exchange_rate->save();
                    $updatedRates[] = $exchange_rate;
                    DB::commit();

                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }

        return $updatedRates;
    }
}

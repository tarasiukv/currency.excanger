<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRateRequest;
use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\Currency;
use App\Services\ExchangeRateService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class MinFinExchangeRateController extends Controller
{
    private $exchangeRateRepository;
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService, ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->exchangeRateService = $exchangeRateService;
    }

    public function index()
    {
        $model = $this->exchangeRateRepository->index();
        return response()->json($model);
    }

    public function update(ExchangeRateRequest $request, Currency $currency)
    {
        $exchange_rate = $request->validated();

        dd($exchange_rate);

        $model = $this->exchangeRateRepository->update($rateData);

        return ExchangeRateResource::collection($model);
    }

    public function fetch()
    {
        $api_keys = $this->exchangeRateService->getApiKey();

//        $url = "https://api.minfin.com.ua/nbu/{$api_keys['minfin']}/";
        $url = "https://v6.exchangerate-api.com/v6/{$api_keys['erapi']}/latest/USD/";

        $client = new Client();
        $response = $client->get($url);

        $data = json_decode($response->getBody(), true);

        dd($data);

        foreach ($data as $currencyCode => $rateData) {
            $currency = $this->exchangeRateService->findByCode($currencyCode);

            if ($currency) {
                // Створюємо об'єкт ExchangeRateRequest
                $request = new ExchangeRateRequest();
                $request->merge($rateData);

//                dd($request);

                $this->update($request, $currency);
            }
        }

        return $response->json();
    }
}

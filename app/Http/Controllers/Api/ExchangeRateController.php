<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRateRequest;
use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Services\ExchangeRateService;

class ExchangeRateController extends Controller
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

    public function update(ExchangeRateRequest $request)
    {
        // TODO: реалізувати валідацію тут на вході

//        dd($request->validated());
//        $model = $this->exchangeRateRepository->update($request);

        return ExchangeRateResource::collection($model);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetch()
    {
//        dd();
//        $this->exchangeRateService->getExchangeRates();
//        // TODO: лишити даний метод максимально чистим.   Отримання даних тут, а формування даних можна перенести в інший сервіс або trait.
//
//        $api_keys = $this->exchangeRateService->getApiKey();
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
//
//            foreach ($data['conversion_rates'] as $currency_code => $rate_data) {
//                $from_currency = $this->exchangeRateService->findByCode($currency_code);
//                $to_currency = $this->exchangeRateService->findByCode($data['base_code']);
//
//                $rate_data = [
//                    'date' => $data['time_last_update_utc'],
//                    'rate' => $rate_data,
//                    'from_currency_id' => $from_currency,
//                    'to_currency_id' => $to_currency,
//                ];

                // TODO: по можливості забрати formRequest з цього методу, тобто щоб перевірка працювала на вході в update

                $rate_data = [
                    'date' => 'Fri, 27 Mar 2020 00:00:00 +0000',
                    'rate' => 436,
                    'from_currency_id' => 12,
                    'to_currency_id' => 1,
                ];

//                $request = new ExchangeRateRequest();
//                $request->merge($rate_data);

//        $this->updateExchangeRate($rate_data);
//                $this->update($rate_data);
//            }

//        return response()->json(['message' => 'Order updated'], 200);
    }
}

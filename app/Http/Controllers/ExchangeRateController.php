<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    private $exchangeRateRepository;
    private $exchangeRateService;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository, ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $model = $this->exchangeRateRepository->index();
            return response()->json($model);
        } catch (\Exception $e) {
            Log::channel('exchangeRate')->error("ExchangeRate Index: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve exchange rates'], 500);
        }
    }


    /**
     * @param Request $request
     * @param ExchangeRate $exchange_rate

     */
    public function update(Request $request, ExchangeRate $exchange_rate)
    {
        //TODO: Завершити метод
        try {
            $this->exchangeRateRepository->update($request->all());
            return new ExchangeRateResource($exchange_rate);
        } catch (\Exception $e) {
            Log::channel('exchangeRate')->error("ExchangeRate Update: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to update exchange rate'], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        try {
            $this->exchangeRateService->getExchangeRates();
            return response()->json(['message' => 'Exchange rates received and updated successfully']);
        } catch (\Exception $e) {
            Log::channel('exchangeRate')->error("ExchangeRate Fetch: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to fetch and update exchange rates'], 500);
        }
    }

    /**
     * Search for exchange rates based on from_currency_id and to_currency_id.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'from_currency_id' => 'required|integer',
            'to_currency_id' => 'required|integer',
        ]);

        $exchangeRates = ExchangeRate::searchByCurrencies(
            $request->from_currency_id,
            $request->to_currency_id
        )->get();

        return response()->json($exchangeRates);
    }
}

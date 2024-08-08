<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Symfony\Component\HttpFoundation\Request;

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
        $model = $this->exchangeRateRepository->index();
        return response()->json($model);
    }

    /**
     * @param Request $request
     * @param ExchangeRate $exchange_rate
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function update(Request $request, ExchangeRate $exchange_rate)
    {
        //TODO: Завершити метод
        $model = $this->exchangeRateRepository->update();
        return ExchangeRateResource::collection($model);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        $this->exchangeRateService->getExchangeRates();

        return response()->json(['message' => 'Exchange rates received and updated successfully']);
    }
}

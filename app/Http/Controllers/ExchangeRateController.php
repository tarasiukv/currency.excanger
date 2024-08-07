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

    public function index()
    {
        $model = $this->exchangeRateRepository->index();
        return response()->json($model);
    }

    public function update(Request $request, ExchangeRate $exchange_rate)
    {
        $model = $this->exchangeRateRepository->update();
        return ExchangeRateResource::collection($model);
    }

    public function fetch()
    {
        $this->exchangeRateService->getExchangeRates();

        return response()->json(['message' => 'Exchange rates received and updated successfully']);
    }
}

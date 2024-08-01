<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;

class ExchangeRateController extends Controller
{
    private $exchangeRateRepository;

    public function __construct(ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    public function index()
    {
        $model = $this->exchangeRateRepository->index();
        return response()->json($model);
    }

    public function update()
    {
        $model = $this->exchangeRateRepository->update();
        return ExchangeRateResource::collection($model);
    }
}

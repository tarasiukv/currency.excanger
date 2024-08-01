<?php

namespace App\Services;

use App\Models\Currency;

class ExchangeRateService
{
    public function findByCode(string $code)
    {
        return Currency::where('code', strtoupper($code))->first();
    }

    public function getApiKey()
    {
        $exchangeRateService = InstanceService::getInstance();
        return $exchangeRateService->getApiKey();
    }
}

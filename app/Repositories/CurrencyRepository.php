<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository
{
    public function findByCode(string $code)
    {
        return Currency::where('code', strtoupper($code))->first();
    }
}

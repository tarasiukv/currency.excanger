<?php

namespace App\Interfaces;

use App\Models\ExchangeRate;

interface ExchangeRateRepositoryInterface
{
    public function index();
    public function update(array $data);
    public function destroy(ExchangeRate $exchange_rate);
}

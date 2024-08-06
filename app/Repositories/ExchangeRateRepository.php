<?php

namespace App\Repositories;

use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\DB;
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

    public function update($data)
    {
        try {
            DB::beginTransaction();
            $exchange_rate = ExchangeRate::updateOrCreate([
                'from_currency_id' => $data['from_currency_id'],
                'to_currency_id' => $data['to_currency_id'],
                'rate' => $data['rate'],
                'date' => $data['date']
            ]);

            $exchange_rate->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}

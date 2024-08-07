<?php

namespace App\Repositories;

use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PharIo\Version\Exception;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    public function index()
    {
        return ExchangeRate::with([
            'fromCurrency',
            'toCurrency',
        ])->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(ExchangeRate $exchange_rate)
    {
        if (!$exchange_rate) {
            return response()->json(['message' => 'There is no exchange rate'], 404);
        }
        if (!$exchange_rate->exists) {
            return response()->json(['error' => 'Exchange rate empty'], 404);

        }
        $exchange_rate->load([
            'fromCurrency',
            'toCurrency',
        ]);
        return new ExchangeRateResource($exchange_rate);
    }

    public function update(array $data)
    {
        try {
            DB::beginTransaction();
            $exchange_rate = ExchangeRate::updateOrCreate([
                'from_currency_id' => $data['from_currency_id'],
                'to_currency_id' => $data['to_currency_id'],
                'rate' => $data['rate'],
            ]);

            $exchange_rate->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExchangeRate $exchange_rate)
    {
        $exchange_rate->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

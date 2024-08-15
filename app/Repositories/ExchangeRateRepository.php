<?php

namespace App\Repositories;

use App\Http\Resources\ExchangeRateResource;
use App\Interfaces\ExchangeRateRepositoryInterface;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PharIo\Version\Exception;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            return ExchangeRate::with(['fromCurrency', 'toCurrency'])->get();

        } catch (\Exception $e) {
            Log::channel('exchangeRate')->error("ExchangeRate Index: {$e->getMessage()}");
            return response()->json(['error' => 'An error occurred while retrieving exchange rates.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ExchangeRate $exchange_rate
     * @return ExchangeRateResource|\Illuminate\Http\JsonResponse
     */
    public function show(ExchangeRate $exchange_rate)
    {
        try {
            if (!$exchange_rate || !$exchange_rate->exists) {
                return response()->json(['error' => 'Exchange rate not found'], 404);
            }
            $exchange_rate->load(['fromCurrency', 'toCurrency']);
            return new ExchangeRateResource($exchange_rate);

        } catch (\Exception $e) {
            Log::channel('exchangeRate')->error("ExchangeRate Show: {$e->getMessage()}");
            return response()->json(['error' => 'An error occurred while retrieving the exchange rate.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $data
     * @return void
     */
    public function update(array $data)
    {
        try {
            DB::beginTransaction();
            $exchange_rate = ExchangeRate::updateOrCreate(
                [
                    'from_currency_id' => $data['from_currency_id'],
                    'to_currency_id' => $data['to_currency_id'],
                ],
                [
                    'rate' => $data['rate'],
                ]
            );

            $exchange_rate->save();
            DB::commit();
            Log::channel('exchangeRate')->info("ExchangeRate Update: Successfully updated exchange rate.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('exchangeRate')->error("ExchangeRate Update: {$e->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ExchangeRate $exchange_rate
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(ExchangeRate $exchange_rate)
    {
        try {
            $exchange_rate->delete();
            Log::channel('exchangeRate')->info("ExchangeRate Destroy: Successfully deleted exchange rate.");
            return response(null, Response::HTTP_NO_CONTENT);

        } catch (\Exception $e) {
            Log::channel('exchangeRate')->error("ExchangeRate Destroy: {$e->getMessage()}");
            return response()->json(['error' => 'An error occurred while deleting the exchange rate.'], 500);
        }
    }
}

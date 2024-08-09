<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use PharIo\Version\Exception;
use Symfony\Component\HttpFoundation\Response;

class TransactionRepository
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Transaction::with([
            'fromCurrency',
            'toCurrency',
        ])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param Transaction $transaction
     * @return TransactionResource|\Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        if (!$transaction) {
            return response()->json(['message' => 'There is no exchange rate'], 404);
        }
        if (!$transaction->exists) {
            return response()->json(['error' => 'Exchange rate empty'], 404);

        }
        $transaction->load([
            'fromCurrency',
            'toCurrency',
        ]);
        return new TransactionResource($transaction);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $data
     * @return void
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $transaction = Transaction::create([
                'user_id' => $data['user_id'],
                'from_currency_id' => $data['from_currency_id'],
                'to_currency_id' => $data['to_currency_id'],
                'amount' => $data['amount'],
                'rate' => $data['rate'],
                'result_amount' => $data['result_amount'],
            ]);
            $transaction->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     *  Update the specified resource in storage.
     *
     * @param array $data
     * @return void
     */
    public function update(array $data)
    {
        try {
            DB::beginTransaction();
            $transaction = Transaction::updateOrCreate([
                'from_currency_id' => $data['from_currency_id'],
                'to_currency_id' => $data['to_currency_id'],
                'rate' => $data['rate'],
            ]);

            $transaction->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

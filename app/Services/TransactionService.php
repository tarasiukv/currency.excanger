<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionService
{
    public function indexTransactions()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $transactions = Transaction::with('fromCurrency', 'toCurrency', 'user')->get();
        } else {
            $transactions = Transaction::where('user_id', $user->id)
                ->with('fromCurrency', 'toCurrency', 'user')
                ->get();
        }

        return response()->json($transactions);
    }

    public function storeTransactions($request)
    {
        $user = Auth::user();

        $from_currency = Currency::where('code', strtoupper($request->from_currency_code))->first();
        $to_currency = Currency::where('code', strtoupper($request->to_currency_code))->first();

        if (!$from_currency || !$to_currency) {
            return response()->json(['error' => 'Invalid currency code'], 400);
        }

        if ($from_currency->code === 'UAH') {
            $resultAmount = $this->convertFromUAH($to_currency, $request->amount);
        } elseif ($to_currency->code === 'UAH') {
            $resultAmount = $this->convertToUAH($from_currency, $request->amount);
        } else {
            $resultAmount = $this->convertBetweenCurrencies($from_currency, $to_currency, $request->amount);
        }

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'from_currency_id' => $from_currency->id,
            'to_currency_id' => $to_currency->id,
            'amount' => $request->amount,
            'rate' => $this->getRate($from_currency, $to_currency),
            'result_amount' => $resultAmount,
        ]);

        return $transaction;
    }

    private function convertFromUAH($to_currency, $amount)
    {
        $exchange_rate = ExchangeRate::where('currency_id', $to_currency->id)->first();
        if (!$exchange_rate) {
            return response()->json(['error' => 'Exchange rate not available'], 400);
        }
        return $amount * $exchange_rate->ask;
    }

    private function convertToUAH($from_currency, $amount)
    {
        $exchange_rate = ExchangeRate::where('currency_id', $from_currency->id)->first();
        if (!$exchange_rate) {
            return response()->json(['error' => 'Exchange rate not available'], 400);
        }
        return $amount / $exchange_rate->bid;
    }

    private function convertBetweenCurrencies($from_currency, $to_currency, $amount)
    {
        $toUAH = $this->convertToUAH($from_currency, $amount);
        return $this->convertFromUAH($to_currency, $toUAH);
    }

    private function getRate($from_currency, $to_currency)
    {
        if ($from_currency->code === 'UAH') {
            return ExchangeRate::where('currency_id', $to_currency->id)->first()->ask;
        } elseif ($to_currency->code === 'UAH') {
            return 1 / ExchangeRate::where('currency_id', $from_currency->id)->first()->bid;
        } else {
            return ExchangeRate::where('currency_id', $to_currency->id)->first()->ask / ExchangeRate::where('currency_id', $from_currency->id)->first()->bid;
        }
    }
}

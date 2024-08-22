<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{

    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        if (Gate::allows('viewAny', Transaction::class)) {
            $transactions = Transaction::with([
                'fromCurrency',
                'toCurrency',
            ])->get();
        } else {
            $transactions = Transaction::with([
                'fromCurrency',
                'toCurrency'
            ])->where('user_id', $user->id)->get();
        }

        return response()->json($transactions);
    }

    /**
     * @param Transaction $transaction
     * @return TransactionResource|\Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        if (!Gate::allows('view', $transaction)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$transaction->exists) {
            return response()->json(['error' => 'Transaction does not exist'], 404);
        }

        $transaction->load([
            'fromCurrency',
            'toCurrency',
        ]);

        return new TransactionResource($transaction);
    }

    /**
     * @param TransactionRequest $request
     * @return void
     */
    public function store(TransactionRequest $request)
    {
        $data = $request->validated();
        $this->transactionRepository->store($data);
    }

    /**
     * @param Transaction $transaction
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function search(Request $request)
    {
        try {
            $user = Auth::user();
            $user_ids = $request['user_ids'];
            $from_currency_ids = $request['from_currency_ids'];
            $to_currency_ids = $request['to_currency_ids'];
            $from_date = $request['from_date']; // ISO 8601  YYYY-MM-DD
            $to_date = $request['to_date']; // ISO 8601   YYYY-MM-DD

            if (Gate::allows('viewAny', Transaction::class)) {
                $transactions = Transaction::filterByUser($user_ids)
                    ->filterByFromCurrency($from_currency_ids)
                    ->filterByToCurrency($to_currency_ids)
                    ->filterByDate($from_date, $to_date)
                    ->with([
                        'fromCurrency',
                        'toCurrency',
                    ])->get();
            } else {
                $transactions = Transaction::filterByUser($user->id)
                    ->filterByFromCurrency($from_currency_ids)
                    ->filterByToCurrency($to_currency_ids)
                    ->filterByDate($from_date, $to_date)
                    ->with([
                        'fromCurrency',
                        'toCurrency',
                    ])->get();
            }

            return response()->json(['data' => $transactions]);
        } catch (Exception $e) {
            return response()->json(['error' => ''], 500);
        }
    }
}

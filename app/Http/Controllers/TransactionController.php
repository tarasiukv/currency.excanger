<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;

class TransactionController extends Controller
{

    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $model = $this->transactionService->indexTransactions();
        return response()->json($model);
    }

    public function store(TransactionRequest $request)
    {
        $model = $this->transactionService->storeTransactions($request);
        return response()->json($model);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Illuminate\Http\Request;
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
        $model = $this->transactionRepository->index();
        return response()->json($model);
    }

    /**
     * @param Transaction $transaction
     * @return TransactionResource|\Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        if (!$transaction) {
            return response()->json(['message' => 'Продукту немає'], 404);
        }
        if (!$transaction->exists) {
            return response()->json(['error' => 'Продукт пустий'], 404);

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
     * @param TransactionRequest $request
     * @param Transaction $transaction
     * @return void
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        //TODO: in progress...
//        $data = $request->validated();
//
//        DB::beginTransaction();
//        try {
//            $transaction->update([
//                'price' => $data['price'],
//                'description' => $data['description'],
//                'feature_description' => $data['feature_description'],
//                'count' => $data['count'],
//                'weight_value' => $data['weight_value'],
//                'product_id' => $data['product_id'],
//                'unit_id' => $data['unit_id'],
//                'packaging_id' => $data['packaging_id'],
//                'consistence_id' => $data['consistence_id'],
//                'producing_country_id' => $data['producing_country_id'],
//                'manufacturer_id' => $data['manufacturer_id'],
//            ]);
//
//            DB::commit();
//        } catch (Exception $e) {
//            DB::rollBack();
//        }
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
        // TODO: in progress...
//        try {
//            $search_text = $request['search_text'];
//            $category_ids = $request['category_ids'];
//            $packaging_ids = $request['packaging_ids'];
//            $consistence_ids = $request['consistence_ids'];
//            $producing_country_ids = $request['producing_country_ids'];
//            $manufacturer_ids = $request['manufacturer_ids'];
//            $min_price = $request['min_price'];
//            $max_price = $request['max_price'];
//
//            $transactions = Transaction::search($search_text)
//                ->filterByPrice($min_price, $max_price)
//                ->filterByCategory($category_ids)
//                ->filterByPackaging($packaging_ids)
//                ->filterByConsistence($consistence_ids)
//                ->filterByCountry($producing_country_ids)
//                ->filterByManufacturer($manufacturer_ids)
//                ->with([''])
//                ->get();
//
//            return response()->json(['data' => $transactions]);
//        } catch (Exception $e) {
//            return response()->json(['error' => 'Помилка під час пошуку продукту'], 500);
//        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $per_page = 100;

        $model = Currency::all();

        return $model;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Currency::create($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        return$currency;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('from_currency_id');
            $table->unsignedBigInteger('to_currency_id');
            $table->decimal('amount', 15, 8);
            $table->decimal('rate', 15, 8);
            $table->decimal('result_amount', 15, 8);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('from_currency_id')->references('id')->on('currencies');
            $table->foreign('to_currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

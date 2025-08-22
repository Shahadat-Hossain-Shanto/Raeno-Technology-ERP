<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('head_code');
            $table->string('head_name');
            $table->string('head_type');
            $table->string('reference_id');
            $table->string('reference_note')->nullable()->default(NULL);
            $table->date('transaction_date');
            $table->double('debit')->nullable()->default(0);
            $table->double('credit')->nullable()->default(0);
            $table->double('balance')->nullable()->default(0);
            $table->string('subscriber_id');
            $table->string('store_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

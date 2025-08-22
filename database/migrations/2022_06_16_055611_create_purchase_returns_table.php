<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('po_no');
            $table->double('total_price');
            $table->double('other_cost')->nullable()->default(0);
            $table->double('total_deduction');
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->integer('return_qty');
            $table->double('price');
            // $table->double('tax_return');
            $table->double('deduction')->nullable()->default(0);
            $table->string('note')->nullable()->default(NULL);
            $table->timestamps();
            $table->bigInteger('subscriber_id');
            $table->bigInteger('user_id');
            $table->bigInteger('store_id');
            $table->string('return_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
}

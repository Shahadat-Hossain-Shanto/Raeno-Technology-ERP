<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_no');
            $table->double('total_price');
            $table->double('total_tax_return');
            $table->double('total_deduction');
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->integer('return_qty');
            $table->double('price');
            $table->double('tax_return');
            $table->double('deduction');
            $table->string('note');
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->bigInteger('subscriber_id');
            $table->bigInteger('user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_returns');
    }
}

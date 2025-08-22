<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoldSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hold_sales', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->bigInteger('client_id');
            $table->string('client_name')->nullable()->default(NULL);
            $table->string('client_mobile')->nullable()->default(NULL);
            $table->bigInteger('productId');
            $table->string('productName');
            $table->string('mrp');
            $table->string('quantity');
            $table->string('totalPrice');
            $table->string('totalDiscount');
            $table->string('totalTax');
            $table->string('availableOffer');
            $table->string('requiredQuantity');
            $table->string('grandTotal');
            $table->string('discount');
            $table->string('offerItemId')->nullable()->default(NULL);
            $table->string('offerName')->nullable()->default(NULL);
            $table->string('offerQuantity');
            $table->string('tax');
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->bigInteger('subscriber_id');
            $table->bigInteger('pos_id');
            $table->bigInteger('store_id');
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
        Schema::dropIfExists('hold_sales');
    }
}

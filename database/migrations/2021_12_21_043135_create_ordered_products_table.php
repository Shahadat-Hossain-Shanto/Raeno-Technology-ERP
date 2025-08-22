<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('productId');
            $table->string('productName');
            $table->integer('quantity');
            $table->unsignedInteger('offerItemId');
            $table->string('offerName');
            $table->integer('offerQuantity');
            $table->double('totalDiscount');
            $table->double('totalPrice');
            $table->double('grandTotal');
            $table->double('totalTax');
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->unsignedInteger('orderId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordered_products');
    }
}

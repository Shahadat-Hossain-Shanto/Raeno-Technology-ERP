<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('supplierId');
            $table->string('store');
            $table->unsignedInteger('poNumber')->unique();
            $table->double('totalPrice');
            $table->double('discount')->nullable()->default(NULL);
            $table->double('grandTotal');
            $table->date('purchaseDate');
            $table->string('purchaseNote')->nullable()->default(NULL);
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            // $table->unsignedInteger('subscriber_id');
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
        Schema::dropIfExists('purchase_products');
    }
}

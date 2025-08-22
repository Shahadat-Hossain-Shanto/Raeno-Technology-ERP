<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedProductTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_product_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('taxId');
            $table->string('taxName');
            $table->double('taxAmount');
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->unsignedInteger('orderId');
            $table->unsignedInteger('productId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordered_product_taxes');
    }
}

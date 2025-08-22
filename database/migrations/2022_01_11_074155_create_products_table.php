<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('productName')->unique();
            $table->string('productLabel');
            $table->string('brand');

            $table->string('category')->nullable()->default(NULL);
            $table->string('category_name')->nullable()->default(NULL);
            $table->string('subcategory_name')->nullable()->default(NULL);

            $table->string('sku')->nullable()->default(NULL);
            $table->string('barcode')->nullable()->default(NULL);

            $table->string('supplier')->nullable()->default(NULL);

            $table->bigInteger('start_stock')->nullable()->default('0');
            $table->bigInteger('safety_stock')->nullable()->default('0');

            $table->string('color')->nullable()->default(NULL);
            $table->string('size')->nullable()->default(NULL);

            $table->string('available_discount')->nullable()->default(NULL);
            $table->double('discount')->nullable()->default('0');
            $table->string('discount_type')->nullable();
            
            $table->string('available_offer')->nullable()->default(NULL);
            $table->string('offerItemId')->nullable()->default(NULL);
            $table->string('freeItemName')->nullable()->default(NULL);
            $table->bigInteger('requiredQuantity')->nullable()->default('0');
            $table->bigInteger('freeQuantity')->nullable()->default('0');

            $table->string('isExcludedTax')->nullable()->default(NULL);
            $table->string('taxName')->nullable()->default(NULL);
            $table->double('tax')->nullable()->default('0');

            $table->string('desc')->nullable()->default(NULL);
            $table->string('productImage')->nullable();

            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->unsignedInteger('subscriber_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('onHand')->default('0');
            $table->bigInteger('productIncoming')->default('0');
            $table->double('mrp')->default('0');
            $table->string('measuringType')->nullable()->default(NULL);
            $table->double('price')->default('0');
            $table->date('purchase_date');
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->string('productId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}

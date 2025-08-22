<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discountId')->nullable();
            $table->string('discountName')->unique();
            $table->string('discountType');
            $table->double('discount');
            $table->string('isRestricted');
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->timestamps();
            $table->string('store');
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
        Schema::dropIfExists('discounts');
    }
}

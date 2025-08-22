<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_images', function (Blueprint $table) {
            $table->id();
            $table->string('imageName')->unique();
            $table->string('extension');
            $table->string('size');
            $table->timestamps();
            $table->bigInteger('expense_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_images');
    }
}

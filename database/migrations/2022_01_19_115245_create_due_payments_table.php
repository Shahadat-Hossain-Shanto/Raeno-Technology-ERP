<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('due_payments', function (Blueprint $table) {
            $table->id();
            $table->double('total');
            $table->double('cash');
            $table->double('card');
            $table->double('mobile_bank');
            $table->double('due_amount');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->bigInteger('clientId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('due_payments');
    }
}

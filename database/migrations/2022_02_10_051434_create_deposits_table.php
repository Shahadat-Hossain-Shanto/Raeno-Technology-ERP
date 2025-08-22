<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->date('deposit_date');
            $table->double('due');
            $table->double('deposit')->nullable()->default('0');
            $table->double('balance');
            $table->string('status');
            $table->string('deposit_type')->nullable()->default(NULL);
            $table->string('note')->nullable()->default(NULL);
            $table->string('image')->nullable()->default(NULL);
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
            $table->bigInteger('client_id');
            $table->bigInteger('store_id');
            $table->bigInteger('salesBy_id');
            $table->string('salesBy_name');
            $table->bigInteger('subscriber_id');
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
        Schema::dropIfExists('deposits');
    }
}

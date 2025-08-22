<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->unique();
            $table->string('name');
            $table->string('type');
            $table->string('email')->nullable()->default(NULL);
            $table->string('address')->nullable()->default(NULL);
            $table->string('note')->nullable()->default(NULL);
            $table->bigInteger('storeId');
            $table->string('image')->nullable()->default(NULL);
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignUpRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sign_up_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('store_name');
            $table->string('store_count');
            $table->string('business_type');
            $table->string('mobile');
            $table->string('package');
            $table->string('address');
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
        Schema::dropIfExists('sign_up_requests');
    }
}

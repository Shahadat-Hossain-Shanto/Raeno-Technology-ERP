<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('org_name');
            $table->string('org_address');
            $table->string('owner_name');
            $table->string('contact_number')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('pos_type');
            $table->string('status');
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('subscribers');
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
}

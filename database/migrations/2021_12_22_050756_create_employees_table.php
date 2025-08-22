<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('empName');
            $table->string('empEmail')->unique();
            $table->string('empMobile')->unique();
            $table->string('password');
            $table->string('role');
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
        Schema::dropIfExists('employees');
    }
}

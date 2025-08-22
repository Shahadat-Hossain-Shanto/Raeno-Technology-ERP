<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->string('employee_name');
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('note')->nullable()->default(NULL);
            $table->string('leave_status')->nullable()->default('pending');
            $table->bigInteger('subscriber_id');
            $table->string('created_by_user');
            $table->string('updated_by_user')->nullable()->default(NULL);
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
        Schema::dropIfExists('employee_leaves');
    }
}

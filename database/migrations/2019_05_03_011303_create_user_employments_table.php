<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_employments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sss')->nullable();
            $table->string('user_id');
            $table->integer('department_id')->nullable();
            $table->integer('position_id')->nullable();
            // $table->integer('accesschart_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('payroll')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->string('remarks')->nullable();
            $table->datetime('last_date_reported')->nullable();
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
        Schema::dropIfExists('user_employments');
    }
}

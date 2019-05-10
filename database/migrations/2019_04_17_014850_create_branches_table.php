<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('machine_number')->nullable(); //for global use
            $table->integer('bsched_id')->nullable(); // human resource
            $table->integer('region_id')->nullable(); //for pending transaction
            $table->string('name')->unique(); //for global use
            $table->string('whscode')->nullable(); //for inventory reconciliation
            $table->string('bm_oic')->nullable(); //for inventory reconciliation
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
        Schema::dropIfExists('branches');
    }
}

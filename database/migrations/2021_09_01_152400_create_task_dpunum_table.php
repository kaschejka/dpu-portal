<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskDpunumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_dpunum', function (Blueprint $table) {
            $table->id();
            $table->string('DPUNUM')->nullable();
            $table->string('manager')->nullable();
            $table->string('worker')->nullable();
            $table->string('state')->nullable();
            $table->json('options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_dpunum');
    }
}

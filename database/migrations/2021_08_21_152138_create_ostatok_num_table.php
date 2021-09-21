<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOstatokNumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ostatok_num', function (Blueprint $table) {
            $table->integer('id_region');
            $table->string('project');
            $table->integer('col_abc')->nullable();
            $table->integer('col_def')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ostatok_num');
    }
}

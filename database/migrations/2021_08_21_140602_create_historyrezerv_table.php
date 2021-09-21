<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryrezervTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historyrezerv', function (Blueprint $table) {
            $table->string('number');
            $table->string('description');
            $table->date('date_issue');
            $table->date('end_date');
            $table->string('author');
            $table->string('manager');
            $table->string('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historyrezerv');
    }
}

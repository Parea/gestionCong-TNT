<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTimeoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_timeoffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motif');
            $table->text('other_motif')->nullable();
            $table->datetime('start_timeoff');
            $table->datetime('end_timeoff');
            $table->integer('numbers_days_taken');
            $table->integer('employee_id');
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
        Schema::dropIfExists('form_timeoffs');
    }
}

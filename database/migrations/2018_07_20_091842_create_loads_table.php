<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();  
            $table->integer('from_id')->unsigned();  
            $table->integer('to_id')->unsigned();  
            $table->string('no')->nullable();
            $table->date('date')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table
            ->foreign('from_id')
            ->references('id')
            ->on('stores')
            ->onDelete('cascade');

            $table
            ->foreign('to_id')
            ->references('id')
            ->on('stores')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loads');
    }
}

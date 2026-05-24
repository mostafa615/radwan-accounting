<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); 
            $table->integer('from_id')->unsigned(); 
            $table->integer('to_id')->unsigned(); 
            $table->string('cost')->nullable();
            $table->string('notes')->nullable();
            $table->date('date')->nullable();
            $table->boolean('pending')->default('1');                                 
            $table->timestamps();

            $table
            ->foreign('from_id')
            ->references('id')
            ->on('reposites')
            ->onDelete('cascade');

            $table
            ->foreign('to_id')
            ->references('id')
            ->on('reposites')
            ->onDelete('cascade');

            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('transactions');
    }
}

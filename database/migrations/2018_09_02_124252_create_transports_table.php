<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned()->nullable(); 
            $table->integer('user_id')->unsigned()->nullable(); 
            $table->integer('branch_id')->unsigned()->nullable(); 
            $table->date('date')->nullable();
            $table->string('cost')->nullable();
            $table->string('rate')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();


            $table
            ->foreign('employee_id')
            ->references('id')
            ->on('employees')
            ->onDelete('cascade');

            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table
            ->foreign('branch_id')
            ->references('id')
            ->on('branches')
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
        Schema::dropIfExists('transports');
    }
}

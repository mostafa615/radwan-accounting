<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->date('date')->nullable();
            $table->string('basic')->default('0')->nullable(); 
            $table->string('loans')->default('0')->nullable(); 
            $table->string('bonus')->default('0')->nullable();
            $table->string('insurance')->default('0')->nullable();
            $table->string('financial_penalties')->default('0')->nullable(); 
            $table->string('net')->default('0')->nullable(); 
            $table->timestamps();


            $table
            ->foreign('employee_id')
            ->references('id')
            ->on('employees')
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
        Schema::dropIfExists('salaries');
    }
}

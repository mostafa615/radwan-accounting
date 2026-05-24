<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('reposite_id')->unsigned(); 
            $table->date('date')->nullable();
            $table->string('notes')->nullable(); 
            $table->string('cost')->nullable();
            $table->boolean('pending')->default('1');
            $table->timestamps();


            $table
            ->foreign('employee_id')
            ->references('id')
            ->on('employees')
            ->onDelete('cascade');

            $table
            ->foreign('reposite_id')
            ->references('id')
            ->on('reposites')
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
        Schema::dropIfExists('loans');
    }
}

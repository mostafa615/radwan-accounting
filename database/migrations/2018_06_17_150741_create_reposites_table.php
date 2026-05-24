<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepositesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reposites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('balance')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('branch_id')->unsigned(); 
            $table->timestamps();


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
        Schema::dropIfExists('reposites');
    }
}

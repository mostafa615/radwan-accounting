<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dailies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tree_id'); 
            $table->string('no')->nullable();
            $table->string('notes')->nullable();
            $table->date('date')->nullable();            
            $table->string('cost')->nullable();
            $table->integer('reposite_id')->unsigned(); 
            $table->integer('user_id')->unsigned(); 
            $table->integer('branch_id')->unsigned(); 
            $table->boolean('pending')->default('1');                                 
            $table->string('type')->nullable();   
            $table->timestamps();


            
            $table
            ->foreign('reposite_id')
            ->references('id')
            ->on('reposites')
            ->onDelete('cascade');

            $table
            ->foreign('branch_id')
            ->references('id')
            ->on('branches')
            ->onDelete('cascade');

            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');


            $table
            ->foreign('tree_id')
            ->references('id')
            ->on('trees')
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
        Schema::dropIfExists('dailies');
    }
}

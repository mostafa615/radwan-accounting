<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
           
            $table->increments('id');
            $table->integer('reposite_id')->unsigned()->nullable(); 
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('branch_id')->unsigned()->nullable();  
            $table->integer('user_id')->unsigned()->nullable();  
            $table->nullableMorphs('accountable'); 
            $table->string('type')->nullable(); 
            $table->string('cost')->default(0);                     
            $table->date('date')->nullable();   
            $table->boolean('pending')->default('1');                                 
            $table->timestamps();

            $table
            ->foreign('reposite_id')
            ->references('id')
            ->on('reposites')
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

            $table
            ->foreign('order_id')
            ->references('id')
            ->on('orders')
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
        Schema::dropIfExists('accounts');
    }
}

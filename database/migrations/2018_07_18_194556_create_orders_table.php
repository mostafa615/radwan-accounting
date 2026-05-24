<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->nullableMorphs('ownerable');
            $table->boolean('is_return')->default('0');
            $table->integer('reposite_id')->unsigned()->nullable();  
            $table->integer('mandator_id')->unsigned()->nullable();  
            $table->integer('user_id')->unsigned()->nullable();  
            $table->integer('branch_id')->unsigned()->nullable();  
            // $table->integer('order_id')->unsigned()->nullable();  
            $table->string('total')->default('0');
            $table->string('vat')->default('0');
            $table->string('discount')->default('0');
            $table->string('final_total')->nullable();
            $table->string('rest')->nullable();
            $table->string('cost')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

           


            $table
            ->foreign('mandator_id')
            ->references('id')
            ->on('mandators')
            ->onDelete('cascade');

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

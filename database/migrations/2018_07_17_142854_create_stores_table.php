<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('phone_1')->nullable();                       
            $table->string('phone_2')->nullable();                       
            $table->string('phone_3')->nullable();                       
            $table->string('address')->nullable();
            $table->enum('type',['فرعي','رئيسي']);     
            $table->integer('user_id')->nullable()->unsigned(); 
            $table->integer('country_id')->nullable()->unsigned();  
            $table->timestamps();


            $table
            ->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table
            ->foreign('country_id')
            ->references('id')
            ->on('countries')
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
        Schema::dropIfExists('stores')->nullable();
    }
}

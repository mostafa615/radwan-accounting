<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();                       
            $table->string('phone_1')->nullable();                       
            $table->string('phone_2')->nullable();                       
            $table->string('phone_3')->nullable();                       
            $table->string('whatsapp')->nullable();   
            $table->string('email')->nullable();                       
            $table->string('address')->nullable();                       
            $table->string('id_image_1')->nullable();                       
            $table->string('id_image_2')->nullable();                       
            $table->string('notes')->nullable(); 
            $table->string('balance')->default('0')->nullable();    
            $table->string('init')->default('0')->nullable();    
            $table->integer('country_id')->nullable()->unsigned();  
            $table->timestamps();
            
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
        Schema::dropIfExists('clients')->nullable();
    }
}

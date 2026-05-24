<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();                       
            $table->string('phone_1')->nullable();                       
            $table->string('phone_2')->nullable();                       
            $table->string('phone_3')->nullable();                       
            $table->string('wechat')->nullable();                       
            $table->string('whatsapp')->nullable();                       
            $table->string('address')->nullable();                       
            $table->string('id_image_1')->nullable();                       
            $table->string('id_image_2')->nullable();                       
            $table->string('notes')->nullable(); 
            $table->string('email')->nullable();
            $table->string('website')->nullable(); 
            $table->string('commercial_record')->nullable();    
            $table->string('tax_card')->nullable();    
            $table->string('balance')->default('0')->nullable();   
            $table->string('init')->default('0')->nullable();    
            $table->integer('country_id')->nullable()->unsigned();
            $table->integer('actor_id')->nullable()->unsigned();  
            $table->timestamps();


            $table
            ->foreign('country_id')
            ->references('id')
            ->on('countries')
            ->onDelete('cascade');

            $table
            ->foreign('actor_id')
            ->references('id')
            ->on('actors')
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
        Schema::dropIfExists('suppliers')->nullable();
    }
}

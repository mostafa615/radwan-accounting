<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->date('date_of_birth')->nullable();                       
            $table->date('date_of_appointment')->nullable(); 
            $table->date('degree')->nullable();                       
            $table->string('phone_1')->nullable();                       
            $table->string('phone_2')->nullable();                       
            $table->string('phone_3')->nullable();                       
            $table->string('whatsapp')->nullable();                       
            $table->string('address')->nullable();  
            $table->string('email')->nullable();                       
            $table->string('id_image_1')->nullable();                       
            $table->string('id_image_2')->nullable();
            $table->string('avatar')->nullable(); 
            $table->string('criminal_record')->nullable();  
            $table->string('cv')->nullable();                       
            $table->string('notes')->nullable(); 
            $table->integer('job_id')->unsigned()->nullable();  
            $table->integer('branch_id')->unsigned()->nullable();  
            $table->integer('country_id')->unsigned()->nullable();  
            $table->string('balance')->default('0')->nullable();    
            $table->timestamps();


            $table
            ->foreign('country_id')
            ->references('id')
            ->on('countries')
            ->onDelete('cascade');


             $table
            ->foreign('branch_id')
            ->references('id')
            ->on('branches')
            ->onDelete('cascade');

            $table
            ->foreign('job_id')
            ->references('id')
            ->on('jobs')
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
        Schema::dropIfExists('employees')->nullable();
    }
}

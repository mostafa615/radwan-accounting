<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name')->nullable();  
            $table->string('password')->nullable();  
            $table->string('name')->nullable();
            $table->string('email')->nullable(); 
            $table->rememberToken();                                                                                                                                                                                                                                                                                                                                                                                                                                  
            $table->integer('branch_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types')
             ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

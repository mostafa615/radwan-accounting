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
            $table->timestamps();
            $table->softDeletes();
            $table->string('email', 191);
            $table->string('name', 191)->nullable();
            $table->string('password', 191);
            $table->enum('admin', ['admin','employee']);
            $table->integer('branch_id');
            $table->string('remember_token', 191)->nullable();
            $table->string('api_token', 191)->nullable();
            $table->string('phone', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}

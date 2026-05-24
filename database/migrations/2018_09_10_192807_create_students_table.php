<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration {

	public function up()
	{
		Schema::create('students', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->string('tel_1')->nullable();
			$table->string('tel_2')->nullable();
			$table->string('tel_3')->nullable();
			$table->string('email');
			$table->string('password');
			$table->string('remember_token')->nullable();
			$table->string('api_token')->nullable();
			$table->string('whatsapp')->nullable();
			$table->integer('informing_mean_id')->nullable();
			$table->integer('branch_id');
		});
	}

	public function down()
	{
		Schema::drop('students');
	}
}
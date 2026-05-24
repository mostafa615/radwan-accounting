<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstructorsTable extends Migration {

	public function up()
	{
		Schema::create('instructors', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->string('email');
			$table->string('password');
			$table->string('remember_token')->nullable();
			$table->string('api_token')->nullable();
			$table->string('tel_1')->nullable();
			$table->string('tel_2')->nullable();
			$table->string('tel_3')->nullable();
			$table->integer('branch_id');
			$table->integer('specialization_id');
			$table->integer('level_id');
		});
	}

	public function down()
	{
		Schema::drop('instructors');
	}
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoursesTable extends Migration {

	public function up()
	{
		Schema::create('courses', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->text('desc')->nullable();
			$table->integer('total_hours')->nullable()->default('0');
			$table->integer('level_id');
			$table->text('notes')->nullable();
			$table->integer('specialization_id');
		});
	}

	public function down()
	{
		Schema::drop('courses');
	}
}
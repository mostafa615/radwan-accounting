<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequiredCourcesTable extends Migration {

	public function up()
	{
		Schema::create('required_cources', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('student_id');
			$table->integer('course_id');
			$table->text('notes')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('required_cources');
	}
}
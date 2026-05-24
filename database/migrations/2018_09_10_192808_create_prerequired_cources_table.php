<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrerequiredCourcesTable extends Migration {

	public function up()
	{
		Schema::create('prerequired_cources', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('parent_id');
			$table->integer('son_id');
		});
	}

	public function down()
	{
		Schema::drop('prerequired_cources');
	}
}
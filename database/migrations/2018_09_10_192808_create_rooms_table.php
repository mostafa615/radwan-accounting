<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoomsTable extends Migration {

	public function up()
	{
		Schema::create('rooms', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->string('type')->nullable();
			$table->integer('capacity')->default('0');
			$table->integer('branch_id');
			$table->text('notes')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('rooms');
	}
}
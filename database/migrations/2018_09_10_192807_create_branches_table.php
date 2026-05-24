<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchesTable extends Migration {

	public function up()
	{
		Schema::create('branches', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name', 191);
			$table->text('address')->nullable();
			$table->string('city', 191)->nullable();
			$table->string('tel_1', 191)->nullable();
			$table->string('tel_2')->nullable();
			$table->string('tel_3')->nullable();
			$table->integer('balance')->default('0');
		});
	}

	public function down()
	{
		Schema::drop('branches');
	}
}
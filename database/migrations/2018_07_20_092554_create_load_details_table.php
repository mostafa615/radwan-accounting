<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('load_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('load_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('quantity')->nullable();
            $table->boolean('pending')->default('1');
            $table->timestamps();

            $table
            ->foreign('load_id')
            ->references('id')
            ->on('loads')
            ->onDelete('cascade');


            $table
            ->foreign('item_id')
            ->references('id')
            ->on('items')
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
        Schema::dropIfExists('load_details');
    }
}

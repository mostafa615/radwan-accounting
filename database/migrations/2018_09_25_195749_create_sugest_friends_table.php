<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSugestFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sugest_friends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('branch_id');
            $table->text('name');
            $table->string('phone',191);
            $table->string('email',191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sugest_friends');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpeningCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id');
            $table->integer('instructor_id');
            $table->integer('branch_id');
            $table->integer('room_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status',['open','start','end']);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('opening_courses');
    }
}

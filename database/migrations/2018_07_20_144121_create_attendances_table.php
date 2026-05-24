<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->date('date')->nullable();
            $table->time('attendance_time')->nullable();
            $table->time('real_attendance_time')->nullable();
            $table->time('abandonment_time')->nullable();
            $table->time('real_abandonment_time')->nullable();
            $table->boolean('absence')->default(0);
            $table->boolean('absence_with_permission')->default(0);
            $table->boolean('absence_with_holiday')->default(0);
            $table->boolean('late')->default(0);
            $table->boolean('late_with_permission')->default(0);
            $table->timestamps();

            $table
            ->foreign('employee_id')
            ->references('id')
            ->on('employees')
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
        Schema::dropIfExists('attendances');
    }
}

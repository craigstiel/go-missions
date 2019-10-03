<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description', 500);
            $table->string('priority');
            $table->integer('status');
            $table->integer('created_by')->unsigned();
            $table->integer('type')->unsigned();
            $table->integer('master')->unsigned();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('master')->references('id')->on('users');
            $table->foreign('type')->references('id')->on('task_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}

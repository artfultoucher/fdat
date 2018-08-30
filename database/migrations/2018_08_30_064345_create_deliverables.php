<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliverables', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('path');
            $table->integer('project_id')->default(0);
            $table->integer('request_id')->default(0);
            $table->integer('uploader_id')->default(0);
            $table->tinyInteger('mark')->nullable();
            $table->boolean('graded')->default(false);
            $table->string('comment')->nullable();
            $table->string('private_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliverables');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title', 200); // max lenght for project titles is 200
            $table->string('type', 16); // max lenght for degree program code
            $table->integer('author')->default(0);
            $table->integer('supervisor')->default(0);
            $table->integer('secondreader')->default(0);
            $table->tinyInteger('visibility')->default(0); // private visibility could also be encoded by secondreader < 0
            $table->boolean('semester_project')->default(true); // students can only be assigned to one semester project but to multiple other projects
            $table->string('abstract', 500);
            $table->string('description', 2000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}

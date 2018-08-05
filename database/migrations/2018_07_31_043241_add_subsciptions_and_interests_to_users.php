<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubsciptionsAndInterestsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('access.table_names.users'), function (Blueprint $table) {
            //
            $table->integer('subscr_mask')->default(0);
            $table->string('interests')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('access.table_names.users'), function (Blueprint $table) {
            //
            $table->dropColumn(['subscr_mask', 'interests']);
        });
    }
}

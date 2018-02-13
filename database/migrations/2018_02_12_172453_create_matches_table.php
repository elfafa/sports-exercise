<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('sport');
            $table->string('competition');
            $table->string('external_id');
            $table->string('team_home_id')->nullable();
            $table->string('team_home_name');
            $table->string('team_away_id')->nullable();
            $table->string('team_away_name');
            $table->string('season');
            $table->string('feed_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}

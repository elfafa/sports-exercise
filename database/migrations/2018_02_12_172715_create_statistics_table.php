<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('match_id')->unsigned()->index();
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->string('status');
            $table->integer('elapsed_time')->default(0);
            $table->timestamp('start_at')->nullable();
            $table->string('top_scorer_name')->nullable();
            $table->string('winner_team')->nullable();
            $table->integer('total_goals')->default(0);
            $table->integer('red_cards')->default(0);
            $table->integer('yellow_cards')->default(0);
            $table->integer('team_home_taskles')->default(0);
            $table->integer('team_home_touches')->default(0);
            $table->integer('team_home_fouls')->default(0);
            $table->integer('team_away_taskles')->default(0);
            $table->integer('team_away_touches')->default(0);
            $table->integer('team_away_fouls')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}

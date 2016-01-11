<?php

namespace Manager\Migrations;

class Maps
{
    /**
     * Runs the migrations.
     *
     * @param Blueprint $table 
     * @return void 
     */
    public static function up($table)
    {
        $table->increments('id');
        
        $table->integer('match_id')->unsigned();
        $table->foreign('match_id')->references('id')
                                   ->on('matches')
                                   ->onDelete('cascade');

        $table->string('map')->default('de_dust2');
        $table->integer('score_a')->default(0);
        $table->integer('score_b')->default(0);
        $table->integer('current_round')->default(0);

        /**
         * Statuses:
         * 
         *  1. Not Started
         *  2. Starting
         *  3. Pre-Game Warmup
         *  4. Knife Round
         *  5. Knife Winners Deciding
         *  6. First Half Warmup
         *  7. First Half
         *  8. Second Half Warmup
         *  9. Second Half
         * 10. Overtime Warmup
         * 11. Overtime
         * 12. Finished
         */
        $table->integer('status')->default(0);
        $table->boolean('is_paused')->default(false);

        $table->string('current_side')->default('ct');
        
        $table->timestamps();
    }
}
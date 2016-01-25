<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Migrations;

class Maps
{
    /**
     * Runs the migrations.
     *
     * @param Blueprint $table
     *
     * @return void
     */
    public function up($table)
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

        /*
         * Statuses:
         * 
         *  0. Not Started
         *  1. Starting
         *  2. Pre-Game Warmup
         *  3. Knife Round
         *  4. Knife Winners Deciding
         *  5. First Half Warmup
         *  6. First Half
         *  7. Second Half Warmup
         *  8. Second Half
         *  9. Overtime Warmup
         * 10. Overtime
         * 11. Finished
         */
        $table->integer('status')->default(0);
        $table->boolean('is_paused')->default(false);

        $table->string('current_side')->default('ct');

        $table->boolean('t_ready')->default(false);
        $table->boolean('ct_ready')->default(false);

        $table->timestamps();
    }
}

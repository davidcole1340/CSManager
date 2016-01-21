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

class Rounds
{
    /**
     * Runs the migrations.
     *
     * @param Blueprint $table
     */
    public static function up($table)
    {
        $table->increments('id');

        $table->integer('map_id')->unsigned();
        $table->foreign('map_id')->references('id')
                                 ->on('maps')
                                 ->onDelete('cascade');

        $table->integer('round');
        $table->string('win_type')->nullable();

        $table->integer('winner_id')->unsigned();
        $table->foreign('winner_id')->references('id')
                                    ->on('teams')
                                    ->onDelete('cascade');
        $table->string('winner_team')->default('t');

        $table->integer('mvp_id')->unsigned();
        $table->foreign('mvp_id')->references('id')
                                 ->on('players')
                                 ->onDelete('cascade');
        $table->string('mvp_text')->nullable();

        $table->string('backup_file')->nullable();

        $table->timestamps();
    }
}

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

class MapScores
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

        $table->string('type')->default('normal');

        $table->integer('team_a_side_1')->default(0);
        $table->integer('team_a_side_2')->default(0);

        $table->integer('team_b_side_1')->default(0);
        $table->integer('team_b_side_2')->default(0);

        $table->timestamps();
    }
}

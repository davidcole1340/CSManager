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

class Matches
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

        $table->integer('event_id')->unsigned();
        $table->foreign('event_id')->references('id')
                                   ->on('events')
                                   ->onDelete('cascade');
        $table->integer('server_id')->unsigned();
        $table->foreign('server_id')->references('id')
                                    ->on('servers')
                                    ->onDelete('cascade');

        $table->integer('team_a')->unsigned();
        $table->foreign('team_a')->references('id')
                                 ->on('teams')
                                 ->onDelete('cascade');
        $table->integer('team_b')->unsigned();
        $table->foreign('team_b')->references('id')
                                 ->on('teams')
                                 ->onDelete('cascade');

        $table->integer('ruleset')->unsigned();
        $table->foreign('ruleset')->references('id')
                                  ->on('rulesets')
                                  ->onDelete('cascade');

        /*
         * Statuses:
         *
         * -1: Finished
         *  0: Not Started
         *  1: Running Map 1
         *  2: Running Map 2
         *  3: Running Map 3
         *  4: Running Map 4
         *  5: Running Map 5
         */
        $table->integer('status')->default(0);
        $table->boolean('starting')->default(false);

        $table->string('password')->nullable();
        $table->string('auth_key')->nullable();

        $table->integer('best_of')->default(1);

        $table->timestamps();
    }
}

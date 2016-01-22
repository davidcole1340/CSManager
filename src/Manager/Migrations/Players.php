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

class Players
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

        $table->integer('map_id')->unsigned();
        $table->foreign('map_id')->references('id')
                                 ->on('maps')
                                 ->onDelete('cascade');

        $table->string('steam_id');
        $table->string('username')->nullable();

        $table->bigInteger('kills')->default(0);
        $table->bigInteger('assists')->default(0);
        $table->bigInteger('deaths')->default(0);

        $table->bigInteger('points')->default(0);
        $table->bigInteger('headshots')->default(0);

        $table->bigInteger('plants')->default(0);
        $table->bigInteger('defuses')->default(0);

        $table->bigInteger('teamkills')->default(0);

        $table->bigInteger('1k')->default(0);
        $table->bigInteger('2k')->default(0);
        $table->bigInteger('3k')->default(0);
        $table->bigInteger('4k')->default(0);
        $table->bigInteger('5k')->default(0);

        $table->bigInteger('1v1')->default(0);
        $table->bigInteger('1v2')->default(0);
        $table->bigInteger('1v3')->default(0);
        $table->bigInteger('1v4')->default(0);
        $table->bigInteger('1v5')->default(0);

        $table->bigInteger('entries')->default(0);

        $table->timestamps();
    }
}

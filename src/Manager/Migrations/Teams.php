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

class Teams
{
    /**
     * Runs the migrations.
     *
     * @param Blueprint $table
     */
    public static function up($table)
    {
        $table->increments('id');

        $table->integer('event_id')->unsigned();
        $table->foreign('event_id')->references('id')
                                   ->on('events')
                                   ->onDelete('cascade');
        $table->string('name');
        $table->string('flag');
        $table->string('logo')->nullable();

        $table->timestamps();
    }
}

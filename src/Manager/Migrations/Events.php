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

class Events
{
    /**
     * Runs the migrations.
     *
     * @param Blueprint $table
     */
    public static function up($table)
    {
        $table->increments('id');

        $table->string('name');
        $table->string('subtitle')->nullable();
        $table->boolean('active')->default(false);
        $table->string('link')->nullable();
        $table->longText('description')->nullable();

        $table->timestamps();
    }
}

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

class Users
{
    /**
     * Creates the table.
     *
     * @param Blueprint $table
     *
     * @return void
     */
    public function up($table)
    {
        $table->increments('id');
        $table->string('username')->unique();
        $table->string('password');
        $table->timestamps();
    }
}

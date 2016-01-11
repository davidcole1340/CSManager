<?php

namespace Manager\Migrations;

class Users
{
    /**
     * Creates the table.
     *
     * @param Blueprint $table
     * @return void
     */
    public static function up($table)
    {
        $table->increments('id');
        $table->string('username')->unique();
        $table->string('password');
        $table->timestamps();
    }
}

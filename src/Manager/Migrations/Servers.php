<?php

namespace Manager\Migrations;

class Servers
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
        
        $table->string('name');
        $table->string('ip');
        $table->integer('port')->default(27015);
        $table->string('gotv_ip');
        $table->string('rcon');
    
        $table->timestamps();
    }
}

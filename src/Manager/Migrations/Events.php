<?php

namespace Manager\Migrations;

class Events
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
        $table->string('subtitle')->nullable();
        $table->boolean('active')->default(false);
        $table->string('link')->nullable();
        $table->longText('description')->nullable();
    
        $table->timestamps();
    }
}

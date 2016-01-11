<?php

namespace Manager\Migrations;

class Teams
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

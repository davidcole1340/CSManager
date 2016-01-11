<?php

namespace Manager\Migrations;

class RoundEvents
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
        
        $table->integer('map_id')->unsigned();
        $table->foreign('map_id')->references('id')
                                 ->on('maps')
                                 ->onDelete('cascade');

        $table->integer('current_round');

        $table->string('type');
        $table->json('text');
    
        $table->timestamps();
    }
}

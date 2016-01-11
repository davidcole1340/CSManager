<?php

namespace Manager\Migrations;

class MapScores
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

        $table->string('type')->default('normal');

        $table->integer('team_a_side_1')->default(0);
        $table->integer('team_a_side_2')->default(0);

        $table->integer('team_b_side_1')->default(0);
        $table->integer('team_b_side_2')->default(0);
    
        $table->timestamps();
    }
}

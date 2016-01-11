<?php

namespace Manager\Migrations;

class Rulesets
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

        $table->integer('max_rounds')->default(15);

        $table->boolean('knife_round')->default(true);

        $table->boolean('overtime_enabled')->default(true);
        $table->bigInteger('overtime_start_money')->default(10000);
        $table->integer('overtime_max_round')->default(3);

        $table->timestamps();
    }
}

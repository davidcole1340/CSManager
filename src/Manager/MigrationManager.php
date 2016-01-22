<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager;

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class MigrationManager
{
    /**
     * The Config instance.
     *
     * @var Config
     */
    protected $config;

    /**
     * Array of migrations.
     *
     * @var array
     */
    protected $migrations = [
        'users' => Migrations\Users::class,
        'rulesets' => Migrations\Rulesets::class,
        'events' => Migrations\Events::class,
        'teams' => Migrations\Teams::class,
        'servers' => Migrations\Servers::class,
        'matches' => Migrations\Matches::class,
        'maps' => Migrations\Maps::class,
        'map_scores' => Migrations\MapScores::class,
        'players' => Migrations\Players::class,
        'round_events' => Migrations\RoundEvents::class,
        'rounds' => Migrations\Rounds::class,
    ];

    /**
     * Creates a Migration Manager instance.
     *
     * @param Config $config
     *
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Runs the migrations.
     *
     * @return bool
     * @return void
     */
    public function run()
    {
        foreach ($this->migrations as $table => $migration) {
            Logger::log("Migrating `{$table}`");
            try {
                $migration = new $migration();
                Capsule::schema()->create($table, function ($table) use ($migration) {
                    $migration->up($table);
                });
            } catch (FatalThrowableError $e) {
                Logger::log("Error migrating table `{$table}`: {$e->getMessage()}", Logger::LEVEL_ERROR);
            } catch (\PDOException $e) {
                Logger::log("Error migrating table `{$table}`, table likely already created.", Logger::LEVEL_ERROR);
            }
        }

        Logger::log('Finished migrating.');

        return true;
    }
}

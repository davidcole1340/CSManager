<?php

namespace Manager;

use Illuminate\Database\Capsule\Manager as Capsule;
use Manager\Config;
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
        'users'         => Migrations\Users::class,

        'rulesets'      => Migrations\Rulesets::class,
        'events'        => Migrations\Events::class,
        'teams'         => Migrations\Teams::class,
        'servers'       => Migrations\Servers::class,
        'matches'       => Migrations\Matches::class,
        'maps'          => Migrations\Maps::class,
        // 'map_match'	=> Migrations\MapMatchPivot::class,
        'map_scores'    => Migrations\MapScores::class,
        'players'       => Migrations\Players::class,
        'round_events'  => Migrations\RoundEvents::class,
        'rounds'        => Migrations\Rounds::class
    ];

    /**
     * Creates a Migration Manager instance.
     *
     * @param Config $config 
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Runs the migrations.
     *
     * @return boolean 
     */
    public function run()
    {
        foreach ($this->migrations as $table => $migration) {
            echo "Migrating `{$table}`\r\n";
            try {
                Capsule::schema()->create($table, function ($table) use ($migration) {
                    $migration::up($table);
                });
            } catch (FatalThrowableError $e) {
                echo "Error migrating table `{$table}`: {$e->getMessage()}\r\n";
            } catch (\PDOException $e) {
                echo "Error migrating table `{$table}`, table likely already created.\r\n";
            }
        }

        echo "Finished migrating.\r\n";

        return true;
    }
}

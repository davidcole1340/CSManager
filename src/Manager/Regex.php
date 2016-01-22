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

use Manager\Models\Map;
use Reflex\Rcon\Rcon;

class Regex
{
    /**
     * The regex patterns.
     *
     * @var array
     */
    protected $patterns = [
        'connected' => [
            'class' => \Manager\Events\UserConnected::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><>" connected, address ""/',
        ],
        'disconnected' => [
            'class' => \Manager\Events\UserDisconnected::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" disconnected \(reason "([A-Za-z]+)"\)/',
            'ignore' => true,
        ],
        'switched_team' => [
            'class' => \Manager\Events\UserSwitchedTeam::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)>" switched from team <([A-Za-z]+)> to <([A-Za-z]+)>/',
            'ignore' => true,
        ],
        'entered_the_game' => [
            'class' => \Manager\Events\UserEnteredTheGame::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><>" entered the game/',
            'ignore' => true,
        ],

        /*
         * User Events
         */
        'purchased' => [
            'class' => \Manager\Events\UserPurchasedItem::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" purchased "([a-z]+)"/',
            'ignore' => true,
        ],
        'user_attacked' => [
            'class' => \Manager\Events\UserAttacked::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" \[([-0-9]+) ([-0-9]+) ([-0-9]+)\] attacked "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" \[([-0-9]+) ([-0-9]+) ([-0-9]+)\] with "([A-Za-z0-9]+)" \(damage "([0-9]+)"\) \(damage_armor "([0-9]+)"\) \(health "([0-9]+)"\) \(armor "([0-9]+)"\) \(hitgroup "([a-z]+)"\)/',
            'ignore' => true,
        ],
        'user_killed' => [
            'class' => \Manager\Events\UserKilled::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" \[([-0-9]+) ([-0-9]+) ([-0-9]+)\] killed "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" \[([-0-9]+) ([-0-9]+) ([-0-9]+)\] with "([a-zA-Z0-9]+)"/',
        ],
        'user_assisted' => [
            'class' => \Manager\Events\UserAssisted::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" assisted killing "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>"/',
        ],
        'threw_something' => [
            'class' => \Manager\Events\UserThrewSomething::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" threw ([A-Za-z0-9]+) \[([-0-9]+) ([-0-9]+) ([-0-9]+)\]/',
            'ignore' => true,
        ],
        'said_something' => [
            'class' => \Manager\Events\UserSaidSomething::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" say "(.+)"/',
        ],
        'said_something_team_chat' => [
            'class' => \Manager\Events\UserSaidSomething::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" say_team "(.+)"/',
        ],

        /*
         * Events
         */
        'nonuser_triggered_event' => [
            'class' => \Manager\Events\NonUserTriggeredEvent::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ ([A-Za-z]+) triggered "(.+)"/',
        ],
        'user_triggered_event' => [
            'class' => \Manager\Events\UserTriggeredEvent::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ "(.+)<([0-9]+)><([A-Z_0-9:]+)><([A-Za-z]+)>" triggered "(.+)"/',
        ],
        'round_end' => [
            'class' => \Manager\Events\RoundEnd::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ Team "([A-Z]+)" scored "([0-9]+)" with "([0-9]+)" players/',
        ],
        'team_triggered_event' => [
            'class' => \Manager\Events\TeamTriggeredEvent::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ Team "([A-Z]+)" triggered "([A-Za-z_]+)" \(([A-Z]+) "([0-9]+")\) \(([A-Z]+) "([0-9]+")\)/',
        ],

        /*
         * Misc
         */
        'rcon_command' => [
            'class' => \Manager\Events\RconCommand::class,
            'pattern' => '/L [0-9\/]+ - [0-9:]+ rcon from "([0-9A-Za-z.:]+)": command "([a-zA-Z0-9_ -]+)"/',
            'ignore' => true,
        ],
    ];

    /**
     * Constructs a new regex matcher.
     *
     * @param Map  $map
     * @param Rcon $rcon
     *
     * @return void
     */
    public function __construct(Map $map, Rcon $rcon)
    {
        $this->map = $map;
        $this->rcon = $rcon;
    }

    /**
     * Attempts to match a Regex pattern.
     *
     * @param string  $data
     * @param Handler $handler
     *
     * @return void
     */
    public function match($data, $handler)
    {
        $this->map = $this->map->fresh();

        foreach ($this->patterns as $regex) {
            if (isset($regex['ignore']) && $regex['ignore']) {
                continue;
            }

            if (preg_match($regex['pattern'], $data, $matches)) {
                (new $regex['class']($this->map, $this->rcon, $handler))->handle($matches);

                return;
            }
        }

        Logger::log("could not match data: {$data}", Logger::LEVEL_DEBUG);
    }
}

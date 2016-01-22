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

use Handler\Regex;
use SteamCondenser\Exceptions\RCONNoAuthException;
use SteamCondenser\Servers\SourceServer;

class Handler
{
    /**
     * The Map that the handler is handling.
     *
     * @var Map
     */
    protected $map;

    /**
     * The UDP stream from the CS:GO server.
     *
     * @var Stream
     */
    protected $stream;

    /**
     * The regex matcher.
     *
     * @var Regex
     */
    protected $regex;

    /**
     * The Rcon client instance.
     *
     * @var SourceServer
     */
    protected $rcon;

    /**
     * Creates a handler instance.
     *
     * @param Map    $map
     * @param Stream $stream
     *
     * @return void
     */
    public function __construct($map, $stream)
    {
        $this->map = $map;
        $this->stream = $stream;
        $this->regex = new Regex();

        $this->initRcon();
    }

    /**
     * Handles incoming data.
     *
     * @param string $data
     *
     * @return void
     */
    public function handleData($data)
    {
        $this->regex->match($data);
    }

    /**
     * Initilizes the map.
     *
     * @return void
     */
    public function initMap()
    {
        $this->rcon->send("log on; logaddress_add \"{$this->map->match->server->ip}:{$this->map->match->server->port}\";");

        $this->rcon->send("changelevel \"{$this->map->map}\";");
        $this->rcon->send("sv_password \"{$this->map->match->password}\";");

        $this->initTeams();
    }

    /**
     * Initilizes the Rcon instance.
     *
     * @return void
     */
    public function initRcon()
    {
        try {
            $rcon = new SourceServer($this->map->match->server->ip, $this->map->match->server->port);
            $rcon->rconAuth($this->map->match->server->rcon);
        } catch (RCONNoAuthException $e) {
            Logger::log("Could not authenticate with the CS:GO server. {$e->getMessage()}", Logger::LEVEL_ERROR);
        }

        $this->rcon = $rcon;
    }

    /**
     * Initilizes the team names.
     *
     * @return void
     */
    public function initTeams()
    {
        $teams[1] = ($this->map->current_side == 'ct') ? $this->map->match->teamA : $this->map->match->teamB;
        $teams[2] = ($this->map->current_side == 't') ? $this->map->match->teamA : $this->map->match->teamB;

        for ($i = 1; $i <= 2; ++$i) {
            $team = $teams[$i];

            $this->rcon->send("mp_teamname_{$i} \"{$team->name}\";");
            $this->rcon->send("mp_teamflag_{$i} \"{$team->flag}\";");

            if (isset($team->logo)) {
                $this->rcon->send("mp_teamlogo_{$i} \"{$team->logo}\";");
            }
        }
    }
}

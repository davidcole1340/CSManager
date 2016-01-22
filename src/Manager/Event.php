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
use SteamCondenser\Servers\SourceServer;

abstract class Event
{
    /**
     * The Map that the event was fired on.
     *
     * @var Map
     */
    protected $map;

    /**
     * The Rcon client that is connected to the server.
     *
     * @var SourceServer
     */
    protected $rcon;

    /**
     * The Handler that is attached to the Map.
     *
     * @var Handler
     */
    protected $handler;

    /**
     * Constructs the event.
     *
     * @param Map          $map
     * @param SourceServer $rcon
     *
     * @return void
     */
    public function __construct(Map $map, SourceServer $rcon, Handler $handler)
    {
        $this->map = $map;
        $this->rcon = $rcon;
        $this->handler = $handler;
    }
}

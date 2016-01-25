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

use Manager\Chat\DefaultChat;
use Manager\Jobs\SetupTeams;
use Manager\Traits\DispatchesJobs;
use Reflex\Rcon\Exceptions\RconAuthException;
use Reflex\Rcon\Exceptions\RconConnectException;
use Reflex\Rcon\Rcon;

class Handler
{
    use DispatchesJobs;

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
     * The Config instance.
     *
     * @var Config
     */
    public $config;

    /**
     * The regex matcher.
     *
     * @var Regex
     */
    protected $regex;

    /**
     * The Rcon client instance.
     *
     * @var Rcon
     */
    public $rcon;

    /**
     * The ReactPHP event loop.
     *
     * @var LoopInterface
     */
    public $loop;

    /**
     * Creates a handler instance.
     *
     * @param Map           $map
     * @param Stream        $stream
     * @param Config        $config
     * @param LoopInterface $loop
     *
     * @return void
     */
    public function __construct($map, $stream, &$config, &$loop)
    {
        $this->map = $map;
        $this->stream = $stream;
        $this->config = $config;
        $this->loop = $loop;

        $this->initRcon();
        $this->regex = new Regex($map, $this->rcon);
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
        $this->regex->match($data, $this);
    }

    /**
     * Initilizes the map.
     *
     * @return void
     */
    public function initMap()
    {
        $this->rcon->exec("log on; logaddress_add \"{$this->map->match->server->ip}:{$this->map->match->server->port}\";");

        $this->rcon->setTimeout(10); // Server doesn't respond until map has fully changed.
        $this->rcon->exec("changelevel \"{$this->map->map}\";");
        $this->rcon->setTimeout();

        $this->rcon->exec("sv_password \"{$this->map->match->password}\";");

        $this->initTeams();
        $this->initSayReady();

        $this->map->status = 3;
        $this->map->save();
    }

    /**
     * Initilizes the Rcon instance.
     *
     * @return void
     */
    public function initRcon()
    {
        try {
            $rcon = new Rcon($this->map->match->server->ip, $this->map->match->server->port, $this->map->match->server->rcon);
            Logger::log("connecting to rcon {$this->map->match->server->ip}:{$this->map->match->server->port}", Logger::LEVEL_DEBUG);
            $rcon->connect();
        } catch (RconConnectException $e) {
            Logger::log("Could not connect to the CS:GO server. {$e->getMessage()}", Logger::LEVEL_ERROR);
        } catch (RconAuthException $e) {
            Logger::log("Could not authenticate with the CS:GO server. {$e->getMessage()}", Logger::LEVEL_ERROR);
        }

        Logger::log('successfully connected to rcon', Logger::LEVEL_DEBUG);

        $this->rcon = $rcon;
        $this->chat = new DefaultChat($this->rcon); // temp
    }

    /**
     * Initilizes the team names.
     *
     * @return void
     */
    public function initTeams()
    {
        $this->dispatch(SetupTeams::class);
    }

    /**
     * Initilizes the timer to say !ready.
     *
     * @return void
     */
    public function initSayReady()
    {
        Logger::log('creating !ready timer, runs every 10 secs', Logger::LEVEL_DEBUG);
        $this->ready = $this->loop->createPeriodicTimer(10, function () {
            $this->chat->sendMessage('Once your team is ready, type !ready in chat.');
        });
    }

    /**
     * Kills the timer to say !ready.
     *
     * @return void
     */
    public function killSayReady()
    {
        Logger::log('killing !ready timer', Logger::LEVEL_DEBUG);
        $this->loop->cancelTimer($this->ready);
    }
}

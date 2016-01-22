<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Chat;

use SteamCondenser\Servers\SourceServer;

class BaseChat implements ChatInterface
{
    /**
     * The SourceServer instance.
     *
     * @var SourceServer
     */
    protected $rcon;

    /**
     * Constructs a chat messenger.
     *
     * @param SourceServer $rcon
     *
     * @return void
     */
    public function __construct(SourceServer $rcon)
    {
        $this->rcon = $rcon;
    }
}

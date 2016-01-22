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

use Reflex\Rcon\Rcon;

class BaseChat implements ChatInterface
{
    /**
     * The Rcon instance.
     *
     * @var Rcon
     */
    protected $rcon;

    /**
     * Constructs a chat messenger.
     *
     * @param Rcon $rcon
     *
     * @return void
     */
    public function __construct(Rcon $rcon)
    {
        $this->rcon = $rcon;
    }

    /**
     * Sends a message to the game chat.
     *
     * @param string $message
     *
     * @return void
     */
    public function sendMessage($message)
    {
    }
}

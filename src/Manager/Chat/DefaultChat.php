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

class DefaultChat extends BaseChat
{
    /**
     * Sends a message to the game chat.
     *
     * @param string $message
     *
     * @return void
     */
    public function sendMessage($message)
    {
        $this->rcon->send("say \"[CSManager] {$message}\"");
    }
}

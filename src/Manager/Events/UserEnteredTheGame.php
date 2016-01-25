<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Events;

use Manager\Event;
use Manager\Models\Player;

class UserEnteredTheGame extends Event
{
    /**
     * Handles the event.
     *
     * @param array $matches
     *
     * @return void
     */
    public function handle($matches)
    {
        list(, $username, , $steamid) = $matches;

        $player = Player::firstOrNew(['map_id' => $this->map->id, 'steam_id' => $steamid]);
        $player->username = $username;
        $player->save();
    }
}

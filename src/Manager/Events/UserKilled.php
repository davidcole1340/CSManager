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

class UserKilled extends Event
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
        $attacker = Player::where('steam_id', $matches[3])->where('map_id', $this->map->id)->first();
        $attacked = Player::where('steam_id', $matches[10])->where('map_id', $this->map->id)->first();

        if (is_null($attacker) || is_null($attacked)) {
            return;
        }

        ++$attacker->kills;
        ++$attacked->deaths;
        $attacker->save();
        $attacked->save();

        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->score_a + $this->map->score_b + 1;
        $re->type = 'kill';
        $re->text = [
            'attacker' => $attacker->id,
            'attacker_location' => $matches[5].' '.$matches[6].' '.$matches[7],
            'attacked' => $attacked->id,
            'attacked_location' => $matches[12].' '.$matches[13].' '.$matches[14],
            'weapon' => $matches[15],
        ];
        $re->save();
    }
}

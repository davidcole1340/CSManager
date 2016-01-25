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
use Manager\Models\RoundEvent;

class UserTriggeredEvent extends Event
{
    /**
     * The Player that triggered the event.
     *
     * @var Player
     */
    protected $player;

    /**
     * Handles the event.
     *
     * @param array $matches
     *
     * @return void
     */
    public function handle($matches)
    {
        $this->player = Player::where('steam_id', $matches[3])->where('map_id', $this->map->id)->first();
        $message = $matches[5];

        if (is_null($this->player)) {
            return;
        }

        $handle = [
            'Planted_The_Bomb',
            'Defused_The_Bomb',
        ];

        if ($key = array_search($message, $handle)) {
            $func = 'handle';
            $func .= ucwords(camel_case($handle[$key]));

            $this->{$func}();
        }
    }

    /**
     * Handles the Planted_The_Bomb event.
     * 
     * @return void
     */
    public function handlePlantedTheBomb()
    {
        ++$this->player->plants;
        $this->player->save();

        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->current_round;
        $re->type = 'bomb_planted';
        $re->text = [
            'planter' => $this->player->id,
        ];
        $re->save();
    }

    /**
     * Handles the Defused_The_Bomb event.
     *
     * @return void
     */
    public function handleDefusedTheBomb()
    {
        ++$this->player->defuses;
        $this->player->save();

        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->current_round;
        $re->type = 'bomb_defused';
        $re->text = [
            'defuser' => $this->player->id,
        ];
        $re->save();
    }
}

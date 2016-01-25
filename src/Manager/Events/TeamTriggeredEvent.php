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
use Manager\Models\RoundEvent;

class TeamTriggeredEvent extends Event
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
        list(, $team, $event, $data) = $matches;

        $handle = [
            'SFUI_Notice_CTs_Win',
            'SFUI_Notice_Terrorists_Win',
        ];

        $key = array_search($message, $handle);

        if ($key !== false) {
            $func = 'handle';
            $func .= ucwords(camel_case($handle[$key]));

            $this->{$func}($data);
        }
    }

    public function handleSFUINoticeCTsWin($data)
    {
        $team = ($this->map->current_side == 'ct') ? $this->map->match->teamA : $this->map->match->teamB;
        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->current_round;
        $re->type = 'round_end';
        $re->data = [
            'team_id' => $team->id,
            'team' => 'CT',
        ];
        $re->save();
    }

    public function handleSFUINoticeTerroristsWin()
    {
        $team = ($this->map->current_side == 't') ? $this->map->match->teamA : $this->map->match->teamB;
        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->current_round;
        $re->type = 'round_end';
        $re->data = [
            'team_id' => $team->id,
            'team' => 'TERRORIST',
        ];
        $re->save();
    }
}

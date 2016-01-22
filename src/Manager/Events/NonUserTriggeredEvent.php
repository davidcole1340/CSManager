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

use Carbon\Carbon;
use Manager\Event;
use Manager\Models\RoundEvent;

class NonUserTriggeredEvent extends Event
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
        $sender = $matcher[1];
        $message = $matcher[2];

        $handle = [
            'Round_Spawn',
            'Round_Start',
            'Round_End',
        ];

        if ($key = array_search($message, $handle)) {
            $func = 'handle';
            $func .= ucwords(camel_case($handle[$key]));

            $this->{$func}();
        }
    }

    /**
     * Handles the Round_Spawn event.
     * 
     * @return void
     */
    public function handleRoundSpawn()
    {
        $this->map->current_round += 1;
        $this->map->save();
    }

    /**
     * Handles the Round_Start event.
     *
     * @return void
     */
    public function handleRoundStart()
    {
        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->current_round;
        $re->type = 'round_started';
        $re->data = [
            'time' => Carbon::now(),
        ];
        $re->save();
    }

    /**
     * Handles the Round_End event.
     *
     * @return void
     */
    public function handleRoundEnd()
    {
        $re = new RoundEvent();
        $re->map_id = $this->map->id;
        $re->current_round = $this->map->current_round;
        $re->type = 'round_ended';
        $re->data = [
            'time' => Carbon::now(),
        ];
        $re->save();
    }
}

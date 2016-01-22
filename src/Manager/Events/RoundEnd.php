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

class RoundEnd extends Event
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
        $side = $matches[1];

        if ($matches[2] == 0) {
            return;
        }

        if ($this->map->status == 4) {
            // todo knife round
            return;
        }

        if ($side == 'TERRORIST') {
            if ($this->map->current_side == 't') {
                $this->map->score_a = $matches[2];
                $this->map->save();
            } else {
                $this->map->score_b = $matches[2];
                $this->map->save();
            }
        } else {
            if ($this->map->current_side == 'ct') {
                $this->map->score_a = $matches[2];
                $this->map->save();
            } else {
                $this->map->score_b = $matches[2];
                $this->map->save();
            }
        }

        if (($this->map->score_a + $this->map->score_b) == $this->map->match->ruleset->max_rounds) {
            // todo handle halftime
        }
    }
}

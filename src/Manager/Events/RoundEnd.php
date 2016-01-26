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
use Manager\Jobs\InitHalftime;
use Manager\Jobs\StartWarmup;
use Manager\Models\RoundEvent;

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
        return;
        list(, $side, $score) = $matches;

        if ($score == 0) {
            return;
        }

        if ($this->map->inWarmup() || $this->map->status == 4) {
            return;
        }

        if ($this->map->status == 3) {
            $re = new RoundEvent();
            $re->map_id = $this->map->id;
            $re->current_round = -1;
            $re->type = 'knife_round_win';
            $re->data = [
                'knife_winner_side' => $side,
            ];
            $re->save();

            $this->rcon->exec('mp_t_default_secondary "weapon_glock"; mp_ct_default_secondary "weapon_hkp2000";');
            $this->rcon->exec('mp_give_player_c4 1;');

            ++$this->map->status;
            $this->map->save();

            if ($side == 'TERRORIST') {
                $winner = $this->map->match->teamB;
            } else {
                $winner = $this->map->match->teamA;
            }

            $this->handler->chat->sendMessage("{$winner->name} won the knife round, type !stay or !switch to change sides.");

            $this->handler->timers['staySwitch'] = $this->handler->loop->addPeriodicTimer(10, function () use ($winner) {
                $this->handler->chat->sendMessage("{$winner->name} won the knife round, type !stay or !switch to change sides.");
            });

            return;
        }

        if ($side == 'TERRORIST') {
            if ($this->map->current_side == 't') {
                $this->map->score_a = $score;
                $this->map->save();
            } else {
                $this->map->score_b = $score;
                $this->map->save();
            }
        } else {
            if ($this->map->current_side == 'ct') {
                $this->map->score_a = $score;
                $this->map->save();
            } else {
                $this->map->score_b = $score;
                $this->map->save();
            }
        }

        $this->handler->chat->sendMessage("{$this->map->match->teamA->name} {$this->map->score_a} - {$this->map->score_b} {$this->map->match->teamB->name}");

        if (($this->map->score_a + $this->map->score_b) == ($this->map->match->ruleset->max_rounds / 2)) {
            ++$this->map->status;
            $this->map->save();

            $this->dispatch(InitHalftime::class);
        }
    }
}

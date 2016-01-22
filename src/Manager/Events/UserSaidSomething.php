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

class UserSaidSomething extends Event
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
        $message = $matches[5];
        $handle = [
            'ready' => 'handleReady', 'r' => 'handleReady',
            'unready' => 'handleUnready',
            'pause' => 'handlePause',
            'unpause' => 'handleUnpause',
            'stay' => 'handleStay',
            'switch' => 'handleSwitch',
        ];
        $prefixes = ['.', '!'];

        if (! array_search($message[0], $prefixes)) {
            return;
        }

        if (isset($handle[substr($message, 1)])) {
            $this->{$handle[substr($message, 1)]}();
        }
    }

    /**
     * Handles the !ready command.
     * 
     * @return void
     */
    public function handleReady()
    {
        if ($this->map->inWarmup()) {
            $team = $matcher[4];

            if ($team == 'TERRORIST') {
                $this->map->t_ready = true;
                $this->map->save();

                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamA;
                } else {
                    $team = $this->map->match->teamB;
                }
            } else {
                $this->map->ct_ready = true;
                $this->map->save();

                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamB;
                } else {
                    $team = $this->map->match->teamA;
                }
            }

            $this->handler->chat->sendMessage("{$team->name} is now ready.");

            if ($this->map->t_ready && $this->map->ct_ready) {
                // todo start match
            }
        }
    }

    /**
     * Handles the !unready command.
     * 
     * @return void
     */
    public function handleUnready()
    {
        if ($this->map->inWarmup()) {
            $team = $matcher[4];

            if ($team == 'TERRORIST') {
                $this->map->t_ready = false;
                $this->map->save();

                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamA;
                } else {
                    $team = $this->map->match->teamB;
                }
            } else {
                $this->map->ct_ready = false;
                $this->map->save();

                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamB;
                } else {
                    $team = $this->map->match->teamA;
                }
            }

            $this->handler->chat->sendMessage("{$team->name} is no longer ready.");
        }
    }

    /**
     * Handles the !pause command.
     * 
     * @return void
     */
    public function handlePause()
    {
        if ($this->map->inGame() && ! $this->map->is_paused) {
        }
    }

    /**
     * Handles the !unpause command.
     * 
     * @return void
     */
    public function handleUnpause()
    {
    }

    /**
     * Handles the !stay command.
     * 
     * @return void
     */
    public function handleStay()
    {
        if ($this->map->status == 5) {
            $re = RoundEvent::where('map_id', $this->map->id)->where('type', 'knife_round_win')->first();
            if (is_null($re)) {
                return;
            }
            $data = json_decode($re->data);

            if ($data->knife_winner_side == $matcher[4]) {
                $this->handler->chat->sendMessage('Sides will remain the same.');

                $this->map->status = 6;
                $this->map->save();

                // todo start warmup
            }
        }
    }

    /**
     * Handles the !switch command.
     * 
     * @return void
     */
    public function handleSwitch()
    {
        if ($this->map->status == 5) {
            $re = RoundEvent::where('map_id', $this->map->id)->where('type', 'knife_round_win')->first();
            if (is_null($re)) {
                return;
            }
            $data = json_decode($re->data);

            if ($data->knife_winner_side == $matcher[4]) {
                $this->handler->chat->sendMessage('Sides will be swapped.');
                $this->rcon->send('mp_swapteams;');

                $this->map->status = 6;
                $this->map->current_side = 't';
                $this->map->save();

                // todo set team names and start warmup
            }
        }
    }
}

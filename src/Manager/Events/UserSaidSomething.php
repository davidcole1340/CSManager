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
use Manager\Logger;
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
            Logger::log('prefix '.$message[0].' does not match any of the specified prefixes', Logger::LEVEL_DEBUG);

            return;
        }

        if (isset($handle[substr($message, 1)])) {
            $this->{$handle[substr($message, 1)]}($matches);
        } else {
            Logger::log("message {$message} shouldn't be handled", Logger::LEVEL_DEBUG);
        }
    }

    /**
     * Handles the !ready command.
     * 
     * @return void
     */
    public function handleReady($matches)
    {
        if ($this->map->inWarmup()) {
            $team = $matches[4];

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
                $this->handler->killSayReady();

                $waittime = $this->handler->config['matches']['ready_wait_time'];

                Logger::log("creating timers, {$waittime} waittime");

                for ($i = 1; $i <= $waittime; ++$i) {
                    $this->handler->loopFunctions['onceTimer']($i, function () use ($i, $waittime) {
                        $diff = $waittime - $i;
                        $this->handler->chat->sendMessage("Match will start in {$diff} second(s).");
                    });
                }
            }
        }
    }

    /**
     * Handles the !unready command.
     * 
     * @return void
     */
    public function handleUnready($matches)
    {
        if ($this->map->inWarmup()) {
            $team = $matches[4];

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
    public function handlePause($matches)
    {
        if ($this->map->inGame() && ! $this->map->is_paused) {
        }
    }

    /**
     * Handles the !unpause command.
     * 
     * @return void
     */
    public function handleUnpause($matches)
    {
    }

    /**
     * Handles the !stay command.
     * 
     * @return void
     */
    public function handleStay($matches)
    {
        if ($this->map->status == 5) {
            $re = RoundEvent::where('map_id', $this->map->id)->where('type', 'knife_round_win')->first();
            if (is_null($re)) {
                return;
            }
            $data = json_decode($re->data);

            if ($data->knife_winner_side == $matches[4]) {
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
    public function handleSwitch($matches)
    {
        if ($this->map->status == 5) {
            $re = RoundEvent::where('map_id', $this->map->id)->where('type', 'knife_round_win')->first();
            if (is_null($re)) {
                return;
            }
            $data = json_decode($re->data);

            if ($data->knife_winner_side == $matches[4]) {
                $this->handler->chat->sendMessage('Sides will be swapped.');
                $this->rcon->exec('mp_swapteams;');

                $this->map->status = 6;
                $this->map->current_side = 't';
                $this->map->save();

                // todo set team names and start warmup
            }
        }
    }
}

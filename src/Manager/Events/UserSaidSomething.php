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
use Manager\Jobs\SetupTeams;
use Manager\Jobs\StartResumeMatch;
use Manager\Jobs\StartWarmup;
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
            'abort' => 'handleAbort',
        ];
        $prefixes = ['.', '!'];

        if (array_search($message[0], $prefixes) === false) {
            Logger::log('prefix '.$message[0].' does not match any of the specified prefixes', Logger::LEVEL_DEBUG);

            return;
        }

        if (isset($handle[substr($message, 1)])) {
            $this->{$handle[substr($message, 1)]}($matches);
        }
    }

    /**
     * Handles the !ready command.
     *
     * @param array $matches();
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

                if ($this->map->current_side == 'ct') {
                    $team = $this->map->match->teamA;
                } else {
                    $team = $this->map->match->teamB;
                }
            }

            $this->handler->chat->sendMessage("{$team->name} is now ready.");

            if ($this->map->t_ready && $this->map->ct_ready) {
                $this->handler->killSayReady();

                $this->map->t_ready = false;
                $this->map->ct_ready = false;
                $this->map->save();

                $delay = $this->handler->config['match']['start_delay'];
                $this->handler->chat->sendMessage("Match will start/resume in {$delay} second(s).");
                $this->handler->timers['startDelay'] = $this->handler->loop->addTimer($delay, function () {
                    $this->dispatch(StartResumeMatch::class);
                });
            }
        }
    }

    /**
     * Handles the !unready command.
     *
     * @param array $matches
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
     * @param array $matches
     *
     * @return void
     */
    public function handlePause($matches)
    {
        if ($this->map->inGame() && ! $this->map->is_paused) {
            $team = $matches[4];

            if ($team == 'TERRORIST') {
                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamA;
                    $this->map->team_paused = 'a';
                } else {
                    $team = $this->map->match->teamB;
                    $this->map->team_paused = 'b';
                }
            } else {
                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamB;
                    $this->map->team_paused = 'b';
                } else {
                    $team = $this->map->match->teamA;
                    $this->map->team_paused = 'a';
                }
            }

            $this->map->is_paused = true;
            $this->map->save();
            $this->rcon->exec('mp_pause_match;');
            $this->handler->chat->sendMessage("{$team->name} has paused the game.");

            if (isset($this->handler->config['match']['pause']['time_limit'])) {
                $timelimit = $this->handler->config['match']['pause']['time_limit'];

                $this->handler->chat->sendMessage("Match will be unpaused in {$timelimit} second(s).");
                $this->handler->timers['unpause'] = $this->handler->loop->addTimer($timelimit, function () use ($timelimit) {
                    $this->map->team_paused = null;
                    $this->map->team_a_unpause = false;
                    $this->map->team_b_unpause = false;
                    $this->map->is_paused = false;
                    $this->map->save();
                    $this->handler->chat->sendMessage("Match unpaused due to being paused for {$timelimit} second(s).");
                });
            }
        }
    }

    /**
     * Handles the !unpause command.
     *
     * @param array $matches
     *
     * @return void
     */
    public function handleUnpause($matches)
    {
        if ($this->map->is_paused) {
            $team = $matches[4];

            if ($team == 'TERRORIST') {
                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamA;
                    $this->map->team_a_unpause = true;
                } else {
                    $team = $this->map->match->teamB;
                    $this->map->team_b_unpause = true;
                }
            } else {
                if ($this->map->current_side == 't') {
                    $team = $this->map->match->teamB;
                    $this->map->team_b_unpause = true;
                } else {
                    $team = $this->map->match->teamA;
                    $this->map->team_a_unpause = true;
                }
            }

            $this->map->save();

            if ($this->map->team_a_unpause && $this->map->team_b_unpause) {
                $this->handler->cancelTimer('unpause');
                $this->map->team_paused = null;
                $this->map->team_a_unpause = false;
                $this->map->team_b_unpause = false;
                $this->map->is_paused = false;
                $this->map->save();
                $this->handler->chat->sendMessage("Match has been unpaused.");
                return;
            }

            $this->handler->chat->sendMessage("{$team->name} wants to unpause. Type !unpause ");
        }
    }

    /**
     * Handles the !stay command.
     *
     * @param array $matches
     *
     * @return void
     */
    public function handleStay($matches)
    {
        if ($this->map->status == 4) {
            $re = RoundEvent::where('map_id', $this->map->id)->where('type', 'knife_round_win')->first();

            if (is_null($re)) {
                return;
            }

            if ($re->data->knife_winner_side == $matches[4]) {
                $this->handler->chat->sendMessage('Sides will remain the same.');

                ++$this->map->status;
                $this->map->save();

                $this->handler->cancelTimer('staySwitch');

                $this->dispatch(StartWarmup::class);
            }
        }
    }

    /**
     * Handles the !switch command.
     *
     * @param array $matches
     *
     * @return void
     */
    public function handleSwitch($matches)
    {
        if ($this->map->status == 4) {
            $re = RoundEvent::where('map_id', $this->map->id)->where('type', 'knife_round_win')->first();
            
            if (is_null($re)) {
                return;
            }

            if ($re->data->knife_winner_side == $matches[4]) {
                $this->handler->chat->sendMessage('Sides will be swapped.');
                $this->rcon->exec('mp_swapteams;');

                $this->map->status = 5;
                $this->map->current_side = 't';
                $this->map->save();

                $this->handler->cancelTimer('staySwitch');

                $this->dispatch(StartWarmup::class);
            }
        }
    }

    /**
     * Handles the !abort command.
     *
     * @param array $matches
     *
     * @return void
     */
    public function handleAbort($matches)
    {
        if ($this->map->inWarmup()) {
            if (isset($this->handler->timers['startDelay'])) {
                $this->handler->cancelTimer('startDelay');
                $this->handler->chat->sendMessage('Match start aborted.');
                $this->dispatch(StartWarmup::class);

                if ($matches[4] == 'TERRORIST') {
                    $this->map->t_ready = false;
                    $this->map->save();
                } else {
                    $this->map->ct_ready = false;
                    $this->map->save();
                }
            }
        }
    }
}

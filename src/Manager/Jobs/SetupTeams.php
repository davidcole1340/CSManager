<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Jobs;

use Manager\Job;

class SetupTeams extends Job
{
    /**
     * Executes the job.
     *
     * @return void
     */
    public function execute()
    {
        $teams[1] = ($this->map->current_side == 'ct') ? $this->map->match->teamA : $this->map->match->teamB;
        $teams[2] = ($this->map->current_side == 'ct') ? $this->map->match->teamB : $this->map->match->teamA;

        for ($i = 1; $i <= 2; ++$i) {
            $team = $teams[$i];

            $this->rcon->exec("mp_teamname_{$i} \"{$team->name}\";");
            $this->rcon->exec("mp_teamflag_{$i} \"{$team->flag}\";");

            if (isset($team->logo)) {
                $this->rcon->exec("mp_teamlogo_{$i} \"{$team->logo}\";");
            }
        }
    }
}

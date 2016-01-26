<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Jobs\Map;

use Manager\Job;

class StartKnife extends Job
{
    /**
     * Executes the job.
     *
     * @return void
     */
    public function execute()
    {
        $this->rcon->exec("exec {$this->map->match->ruleset->config_file};");
        $this->rcon->exec('mp_t_default_secondary ""; mp_ct_default_secondary "";');
        $this->rcon->exec('mp_roundtime 60; mp_roundtime_defuse 60;');
        $this->rcon->exec('mp_startmoney 0; mp_give_player_c4 0;');

        if ($this->handler->config['match']['knife']['armor']) {
            $this->rcon->exec('mp_free_armor 1;');
        }

        $this->rcon->exec('mp_restartgame 3;');

        $this->handler->loop->addTimer(4, function () {
            ++$this->map->status;
            $this->map->save();

            for ($i = 0; $i < 3; ++$i) {
                $this->handler->chat->sendMessage('KNIFE!');
            }
        });
    }
}

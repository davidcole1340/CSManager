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
use Manager\Jobs\InitBackup;

class StartFirstHalf extends Job
{
    /**
     * Executes the job.
     *
     * @return void
     */
    public function execute()
    {
        $this->rcon->exec("exec {$this->map->match->ruleset->config_file};");
        $this->rcon->exec('mp_warmup_pausetimer 0; mp_warmup_end; mp_restartgame 3;');
        
        $this->handler->loop->addTimer(2, function () {
            $this->rcon->exec('mp_restartgame 1;');
            $this->dispatch(InitBackup::class);

            ++$this->map->status;
            $this->map->save();

            for ($i = 0; $i < 3; ++$i) {
                $this->handler->chat->sendMessage('LIVE!');
            }
        });
    }
}

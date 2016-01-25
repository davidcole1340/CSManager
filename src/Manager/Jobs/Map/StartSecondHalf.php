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

class StartSecondHalf extends Job
{
    /**
     * Executes the job.
     *
     * @return void
     */
    public function execute()
    {
        $this->rcon->exec('mp_halftime_pausetimer 0;');

        $this->map->status++;
        $this->map->save();

        for ($i = 0; $i < 3; ++$i) {
            $this->handler->chat->sendMessage('LIVE!');
        }
    }
}

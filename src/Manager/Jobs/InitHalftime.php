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

class InitHalftime extends Job
{
    /**
     * Executes the job.
     *
     * @return void
     */
    public function execute()
    {
        $this->rcon->exec('mp_halftime 1; mp_halftime_duration 60; mp_halftime_pausetimer 1;');
    }
}

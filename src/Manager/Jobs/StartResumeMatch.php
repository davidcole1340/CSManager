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
use Manager\Jobs\Map\StartFirstHalf;
use Manager\Jobs\Map\StartKnife;
use Manager\Jobs\Map\StartOvertime;
use Manager\Jobs\Map\StartSecondHalf;
use Manager\Traits\DispatchesJobs;

class StartResumeMatch extends Job
{
    use DispatchesJobs;

    /**
     * Executes the job.
     *
     * @return void
     */
    public function execute()
    {
        if ($this->map->status == 2) {
            $this->dispatch(StartKnife::class); // Knife Round
        } elseif ($this->map->status == 5) {
            $this->dispatch(StartFirstHalf::class); // First Half
        } elseif ($this->map->status == 7) {
            $this->dispatch(StartSecondHalf::class); // Second Half
        } elseif ($this->map->status == 9) {
            $this->dispatch(StartOvertime::class); // Overtime
        }
    }
}

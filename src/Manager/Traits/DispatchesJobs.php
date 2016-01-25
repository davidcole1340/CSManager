<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Traits;

trait DispatchesJobs
{
    /**
     * Dispatches a job.
     *
     * @param mixed $class
     * @param array $params
     *
     * @return mixed
     */
    public function dispatch($class, array $params = [])
    {
        $class = new $class($this->map, $this->rcon, $this->handler);

        return call_user_func_array([$class, 'execute'], $params);
    }
}

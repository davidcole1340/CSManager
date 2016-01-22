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

class UserDisconnected extends Event
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
        /*
    	 * Does nothing by default. If you want it to
    	 * do something when an Rcon command is sent
    	 * feel free to.
    	 *
    	 * If you do decide to make this do something,
    	 * make sure to go into src/Manager/Regex.php
    	 * and remove the 'ignore' => true part.
    	 */
        return;
    }
}

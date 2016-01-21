<?php

/*
 * This file is apart of the CSManager project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE file.
 */

namespace Manager\Models;

use Manager\Model;

class Team extends Model
{
    /**
     * Relationship between multiple Rounds.
     *
     * @return HasMany
     */
    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    /**
     * Relationship between an Event.
     *
     * @return BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

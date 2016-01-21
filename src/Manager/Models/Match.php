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

class Match extends Model
{
    /**
     * Relationship between multiple maps.
     *
     * @return HasMany
     */
    public function maps()
    {
        return $this->hasMany(Map::class);
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

    /**
     * Relationship between a Server.
     *
     * @return BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Relationship between a Team.
     *
     * @return HasOne
     */
    public function teamA()
    {
        return $this->hasOne(Team::class, 'id', 'team_a');
    }

    /**
     * Relationship between a Team.
     *
     * @return HasOne
     */
    public function teamB()
    {
        return $this->hasOne(Team::class, 'id', 'team_a');
    }

    /**
     * Relationship between a Ruleset.
     *
     * @return BelongsTo
     */
    public function ruleset()
    {
        return $this->belongsTo(Ruleset::class);
    }
}

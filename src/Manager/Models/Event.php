<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Match;
use Manager\Models\Team;

class Event extends Model
{
    /**
     * Relationship between multiple matches.
     *
     * @return HasMany 
     */
    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    /**
     * Relationship between multiple teams.
     *
     * @return HasMany 
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}

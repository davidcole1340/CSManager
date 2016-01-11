<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Match;

class Ruleset extends Model
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
}

<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Map;
use Manager\Models\Round;

class Player extends Model
{
    /**
     * Relationship between a Map.
     *
     * @return BelongsTo 
     */
    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Relationship between multiple Rounds.
     *
     * @return HasMany 
     */
    public function rounds()
    {
        return $this->hasMany(Round::class);
    }
}

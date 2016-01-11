<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Event;
use Manager\Models\Round;

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

<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Event;
use Manager\Models\Map;
use Manager\Models\Ruleset;
use Manager\Models\Server;
use Manager\Models\Team;

class Match extends Model
{
    /**
     * Relationship between multiple maps
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

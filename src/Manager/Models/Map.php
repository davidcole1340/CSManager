<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\MapScore;
use Manager\Models\Match;
use Manager\Models\Player;
use Manager\Models\Round;
use Manager\Models\RoundEvent;

class Map extends Model
{
    /**
     * Relationship between a Match.
     *
     * @return BelongsTo
     */
    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    /**
     * Relationship between multiple MapScores.
     *
     * @return HasMany
     */
    public function mapscores()
    {
        return $this->hasMany(MapScore::class);
    }

    /**
     * Relationship between multiple players.
     *
     * @return HasMany 
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Relationship between multiple RoundEvents.
     *
     * @return HasMany 
     */
    public function roundevents()
    {
        return $this->hasMany(RoundEvent::class);
    }

    /**
     * Relationship between mutliple Rounds.
     *
     * @return HasMany 
     */
    public function rounds()
    {
        return $this->hasMany(Round::class);
    }
}

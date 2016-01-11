<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Map;
use Manager\Models\Player;
use Manager\Models\Team;

class RoundEvent extends Model
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
     * Relationship between a Team.
     *
     * @return BelongsTo 
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relationship between a Player.
     *
     * @return BelongsTo 
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}

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

class RoundEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'map_id', 'current_round', 'type', 'text',
    ];

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

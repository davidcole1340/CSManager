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

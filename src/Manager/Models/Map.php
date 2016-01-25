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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'match_id', 'map', 'score_a', 'score_b', 'current_round', 'status',
        'is_paused', 'current_side', 't_ready', 'ct_ready',
    ];

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

    /**
     * Checks if the map is in warmup.
     * 
     * @return bool
     */
    public function inWarmup()
    {
        return $this->status == 2 || $this->status == 5 || $this->status == 7 || $this->status == 9;
    }

    /**
     * Checks if the map is in gametime.
     * 
     * @return bool
     */
    public function inGame()
    {
        return $this->status == 6 || $this->status == 8 || $this->status == 10;
    }
}

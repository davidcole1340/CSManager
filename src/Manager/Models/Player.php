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

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'map_id', 'steam_id', 'username',
        'kills', 'assists', 'deaths',
        'points', 'headshots',
        'plants', 'defuses', 'teamkills',
        '1k', '2k', '3k', '4k', '5k',
        '1v1', '1v2', '1v3', '1v4', '1v5',
        'entries',
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
     * Relationship between multiple Rounds.
     *
     * @return HasMany
     */
    public function rounds()
    {
        return $this->hasMany(Round::class);
    }
}

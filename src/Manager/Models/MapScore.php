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

class MapScore extends Model
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
}

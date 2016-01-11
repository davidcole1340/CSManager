<?php

namespace Manager\Models;

use Manager\Model;
use Manager\Models\Map;

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

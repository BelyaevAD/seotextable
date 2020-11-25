<?php

namespace Belyaevad\SeoTextable\Models;

use Illuminate\Database\Eloquent\Model;

class SeoTextable extends Model
{
    protected $fillable
        = [
            'textable_id',
            'textable_type',
            'has_read',
            'links',
            'has_links',
        ];

    protected $casts
        = [
            'links' => 'json',
        ];

    public function textable()
    {
        return $this->morphTo();
    }
}

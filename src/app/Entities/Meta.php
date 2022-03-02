<?php

namespace VCComponent\Laravel\Meta\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meta extends Model
{
    protected $fillable = [
        'key',
        'value',
        'metable_id',
        'metable_type',
    ];

    public function schema()
    {
        return $this->belongsTo(MetaSchema::class, 'key', 'key');
    }

    public function metable()
    {
        return $this->morphTo();
    }
}

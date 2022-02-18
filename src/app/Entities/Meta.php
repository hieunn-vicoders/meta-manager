<?php

namespace VCComponent\Laravel\Meta\Entities;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{

    protected $fillable = [
        'schema_id',
        'value',
        'metable_id',
        'metable_type',
    ];

    public function schema()
    {
        return $this->belongsTo(MetaSchema::class);
    }
}

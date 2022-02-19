<?php

namespace VCComponent\Laravel\Meta\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class MetaSchemaOption extends Model
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'key',
        'value',
        'schema_id'
    ];

    public function sluggable()
    {
        return [
            'key' => [
                'source' => 'value',
            ],
        ];
    }

    public function schema()
    {
        return $this->belongsTo(MetaSchema::class);
    }
}

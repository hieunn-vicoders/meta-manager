<?php

namespace VCComponent\Laravel\Meta\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class MetaSchemaType extends Model
{
    use Sluggable, SluggableScopeHelpers;
    protected $fillable = [
        'name',
    ];
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}

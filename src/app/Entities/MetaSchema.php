<?php

namespace VCComponent\Laravel\Meta\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class MetaSchema extends Model
{
    use Sluggable, SluggableScopeHelpers;

    protected $fillable = [
        'key',
        'label',
        'schema_rule_id',
        'schema_type_id',
        'type'
    ];

    public function sluggable()
    {
        return [
            'key' => [
                'source' => 'label',
            ],
        ];
    }

    public function rule()
    {
        return $this->belongsTo(MetaSchemaRule::class);
    }

    public function type()
    {
        return $this->belongsTo(MetaSchemaType::class);
    }
    
    public function options()
    {
        return $this->hasMany(MetaSchemaOption::class);
    }
}

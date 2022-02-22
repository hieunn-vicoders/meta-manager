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

    public function schemaRule()
    {
        return $this->belongsTo(MetaSchemaRule::class, 'schema_rule_id');
    }

    public function schemaType()
    {
        return $this->belongsTo(MetaSchemaType::class, 'schema_type_id');
    }
    
    public function schemaOptions()
    {
        return $this->hasMany(MetaSchemaOption::class, 'schema_id');
    }
}

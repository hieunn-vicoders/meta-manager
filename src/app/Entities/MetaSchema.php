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
        'schema_type_id',
        'metable_type'
    ];

    public function sluggable()
    {
        return [
            'key' => [
                'source' => 'label',
            ],
        ];
    }

    public function schemaRules()
    {
        if (isset(config('meta.models')['meta-schema-rule'])) {
            return $this->belongsToMany(config('meta.models.meta-schema-rule'), 'meta_schema_ruleables', 'schema_rule_id', 'schema_id');
        } else {
            return $this->belongsToMany(MetaSchemaRule::class, 'meta_schema_ruleables', 'schema_rule_id', 'schema_id');
        }
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

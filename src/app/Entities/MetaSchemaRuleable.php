<?php

namespace VCComponent\Laravel\Meta\Entities;

use Illuminate\Database\Eloquent\Model;

class MetaSchemaRuleable extends Model
{
    protected $fillable = [
        'schema_id',
        'schema_rule_id',
    ];

    public function schemaRules()
    {
        if (isset(config('meta.models')['meta-schema'])) {
            return $this->belongsToMany(config('meta.models.meta-schema'), 'meta_schema_ruleables', 'schema_id', 'schema_rule_id');
        } else {
            return $this->belongsToMany(MetaSchema::class, 'metable', 'meta_schema_ruleables', 'schema_id', 'schema_rule_id');
        }
    }
}

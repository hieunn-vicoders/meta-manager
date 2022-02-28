<?php

namespace VCComponent\Laravel\Meta\Entities;

use Illuminate\Database\Eloquent\Model;

class MetaSchemaRule extends Model
{
    protected $fillable = [
        'name',
    ];

    public function schemas()
    {
        if (isset(config('meta.models')['meta-schema'])) {
            return $this->belongsToMany(config('meta.models.meta-schema'), 'meta_schema_ruleables', 'schema_rule_id', 'schema_id');
        } else {
            return $this->belongsToMany(MetaSchema::class, 'metable', 'meta_schema_ruleables', 'schema_rule_id', 'schema_id');
        }
    }
}

<?php

namespace VCComponent\Laravel\Meta\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\Meta\Transformers\MetaSchemaRuleTransformer;
use VCComponent\Laravel\Meta\Transformers\MetaSchemaTypeTransformer;

class MetaSchemaTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'rule',
        'type',
        'options'
    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'                => $model->id,
            'key'               => $model->key,
            'label'             => $model->label,
            'schema_type_id'    => $model->schema_type_id,
            'schema_rule_id'    => $model->schema_rule_id,
            'type'              => $model->type,
            'timestamps'     => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeType($model)
    {
        if ($model->type) {
            return $this->item($model->type, new MetaSchemaTypeTransformer());
        }
    }

    public function includeRule($model)
    {
        if ($model->rule) {
            return $this->item($model->rule, new MetaSchemaRuleTransformer());
        }
    }

    public function includeOptions($model)
    {
        if ($model->options) {
            return $this->collection($model->options, new MetaSchemaOptionTransformer());
        }
    }
}

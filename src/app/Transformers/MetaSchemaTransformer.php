<?php

namespace VCComponent\Laravel\Meta\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\Meta\Transformers\MetaSchemaRuleTransformer;
use VCComponent\Laravel\Meta\Transformers\MetaSchemaTypeTransformer;

class MetaSchemaTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'schemaRules',
        'schemaType',
        'schemaOptions'
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

    public function includeSchemaType($model)
    {
        if ($model->schemaType) {
            return $this->item($model->schemaType, new MetaSchemaTypeTransformer());
        }
    }

    public function includeSchemaRules($model)
    {
        if ($model->schemaRules) {
            return $this->collection($model->schemaRules, new MetaSchemaRuleTransformer());
        }
    }

    public function includeSchemaOptions($model)
    {
        if ($model->schemaOptions) {
            return $this->collection($model->schemaOptions, new MetaSchemaOptionTransformer());
        }
    }
}

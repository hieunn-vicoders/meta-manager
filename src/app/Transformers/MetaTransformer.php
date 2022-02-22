<?php

namespace VCComponent\Laravel\Meta\Transformers;

use League\Fractal\TransformerAbstract;

class MetaTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'schema'
    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'    => (int) $model->id,
            'schema_id' => $model->schema_id,
            'value' => $model->value,
            'metable_id' => $model->metable_id,
            'metable_type' => $model->metable_type,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeSchema($model)
    {
        if ($model->schema) {
            return $this->item($model->schema, new MetaSchemaTransformer());
        }
    }
}

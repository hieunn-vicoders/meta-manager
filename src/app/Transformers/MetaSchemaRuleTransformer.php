<?php

namespace VCComponent\Laravel\Meta\Transformers;

use League\Fractal\TransformerAbstract;

class MetaSchemaRuleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'         => $model->id,
            'name'       => $model->name,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}

<?php

namespace VCComponent\Laravel\Meta\Traits;

use VCComponent\Laravel\Meta\Entities\MetaSchema;

trait HasMetaFromRequest 
{
    public function filterMetaRequestData($request, $type) 
    {
        $meta_schema_keys = MetaSchema::where('type', $type)->pluck('key')->toArray();

        return collect($request->all())->filter(function($data, $key) use ($meta_schema_keys) {
            return in_array($key, $meta_schema_keys);
        })->toArray();
    }

    public function storeMetaRequestData($data, $model)
    {
        $model->meta()->delete();

        // $data = collect($data)->map(function ($item) use ($model) {
        //     return [
        //         ''
        //     ]
        // })->toArray();

        $model->meta()->createMany($data);
    }
}
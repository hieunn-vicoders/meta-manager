<?php

namespace VCComponent\Laravel\Meta\Validators;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use VCComponent\Laravel\Meta\Entities\MetaSchema;
use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class MetaValidator extends AbstractValidator
{
    protected $rules = [
        'RULE_ADMIN_CREATE' => [
            'metable_id' => ['required'],
            'metable_type' => ['required'],
        ],
        'RULE_ADMIN_UPDATE' => [
            'metable_id' => ['required'],
            'metable_type' => ['required'],
        ],
        'HAS_VALUE' => [
            'value' => ['required'],
            'schema_id' => ['required'],
        ]
    ];

    public function isSchemaValid(Request $request, $meta_schemas)
    {
        $rules = $meta_schemas->map(function ($item) {
            return [$item->key => $item->schemaRule->name];
        })->toArray();

        $validator = Validator::make($request->get('meta'), $rules);
        if ($validator->fails()) {
            throw new Exception($validator->errors(), 402);
        }
        return true;
    }
}

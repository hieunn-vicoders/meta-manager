<?php

namespace VCComponent\Laravel\Meta\Validators;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
        ],
        'GET_META' => [
            'metable_id' => ['required'],
            'metable_type' => ['required'],
        ]
    ];

    public function isSchemaValid(Request $request, $meta_schemas)
    {
        $rules = $meta_schemas->mapWithKeys(function ($item) {
            return [$item->key => $item->schemaRules->pluck('name')->toArray()];
        })->toArray();

        return $this->isValidRule($request->get('meta'), $rules);
    }

    public function isValidRule($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->messages());
        }

        return true;
    }
}

<?php

namespace VCComponent\Laravel\Meta\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class MetaSchemaValidator extends AbstractValidator
{
    protected $rules = [
        'RULE_ADMIN_CREATE' => [
            'label'          => ['required'],
            'schema_type_id' => ['required'],
            'schema_rule_id' => ['required'],
            'type'      => ['required'],
        ],
        'RULE_ADMIN_UPDATE' => [
            'label'          => ['required'],
            'schema_type_id' => ['required'],
            'schema_rule_id' => ['required'],
            'type'      => ['required'],
        ],
    ];
}

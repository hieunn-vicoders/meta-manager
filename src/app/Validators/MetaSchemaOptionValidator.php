<?php

namespace VCComponent\Laravel\Meta\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class MetaSchemaOptionValidator extends AbstractValidator
{
    protected $rules = [
        'RULE_ADMIN_CREATE' => [
            'value'         => ['required'],
            'schema_id'     => ['required'],
        ],
        'RULE_ADMIN_UPDATE' => [
            'value'         => ['required'],
            'schema_id'     => ['required'],
        ],
    ];
}

<?php

namespace VCComponent\Laravel\Meta\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class MetaValidator extends AbstractValidator
{
    protected $rules = [
        'RULE_ADMIN_CREATE' => [
            'metable_id' => 'require',
            'metable_type' => 'require,'
        ],
        'RULE_ADMIN_UPDATE' => [
            'metable_id' => 'require',
            'metable_type' => 'require,'
        ],
        'HAS_VALUE' => [
            'value' => 'require',
        ]
    ];
}

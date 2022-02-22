<?php

return [
    'models'          => [
        'meta' => VCComponent\Laravel\Meta\Entities\Meta::class,
    ],

    'transformers'    => [
        'meta' => VCComponent\Laravel\Meta\Transformers\MetaTransformer::class,
    ],

    'auth_middleware' => [
        'admin'    => [
            [
                'middleware' => '',
                'except'     => [],
            ]
        ],
    ],

];

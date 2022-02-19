<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {
        $api->get('meta/schema-rules', 'VCComponent\Laravel\Meta\Http\Controllers\Api\Admin\MetaSchemaRuleController@index');
        $api->get('meta/schema-types', 'VCComponent\Laravel\Meta\Http\Controllers\Api\Admin\MetaSchemaTypeController@index');
        $api->resource('metas', 'VCComponent\Laravel\Meta\Http\Controllers\Api\Admin\MetaController');
        $api->resource('meta/schemas', 'VCComponent\Laravel\Meta\Http\Controllers\Api\Admin\MetaSchemaController');
        $api->resource('meta/schema/options', 'VCComponent\Laravel\Meta\Http\Controllers\Api\Admin\MetaSchemaOptionController');
    });
});

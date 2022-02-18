<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchema;

$factory->define(MetaSchema::class, function (Faker $faker) {
    $value = $faker->name;
    $key = Str::slug($value);
    return [
        'value' => $value,
        'key' => $key,
    ];
});

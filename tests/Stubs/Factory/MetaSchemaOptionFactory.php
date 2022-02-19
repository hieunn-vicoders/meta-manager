<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchemaOption;

$factory->define(MetaSchemaOption::class, function (Faker $faker) {
    $value = $faker->name;
    $key = Str::slug($value);
    return [
        'value' => $value,
        'key' => $key,
    ];
});

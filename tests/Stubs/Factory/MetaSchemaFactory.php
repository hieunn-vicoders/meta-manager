<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\MetaSchema;

$factory->define(MetaSchema::class, function (Faker $faker) {
    $label = $faker->name;
    $key = Str::slug($label);
    return [
        'label' => $label,
        'key' => $key,
        'type'     => $faker->word,
    ];
});

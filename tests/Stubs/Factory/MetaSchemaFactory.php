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
        'schema_rule_id' => rand(1, 5),
        'schema_type_id' => rand(1, 9),
        'type'     => $faker->word,
    ];
});

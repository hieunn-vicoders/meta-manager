<?php

use Faker\Generator as Faker;
use VCComponent\Laravel\Meta\Test\Stubs\Entities\Meta;

$factory->define(Meta::class, function (Faker $faker) {
    return [
        'value'     => $faker->name,
        'metable_id' => rand(1, 31),
        'metable_type' => 'posts'
    ];
});

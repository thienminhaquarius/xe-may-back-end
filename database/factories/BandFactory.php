<?php

use Faker\Generator as Faker;

$factory->define(App\Band::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
    ];
});

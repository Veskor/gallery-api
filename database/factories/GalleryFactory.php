<?php

use Faker\Generator as Faker;

$factory->define(App\Gallery::class, function (Faker $faker) {
    return [
        'name' => $faker->text(40),
        'description' => $faker->text(100),
        'owner_id' => \App\User::all()->random()->id

    ];
});


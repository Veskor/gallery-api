<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->text(300),
        'owner_id' => \App\User::all()->random()->id,
        'gallery_id' => \App\Gallery::all()->random()->id,
    ];
});

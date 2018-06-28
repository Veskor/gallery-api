<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    static $order = 1;

    return [
        'url' => $faker->imageUrl(). '.jpg',
        'gallery_id' => \App\Gallery::all()->random()->id,
        'order' => $order++

    ];
});

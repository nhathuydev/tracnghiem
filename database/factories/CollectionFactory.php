<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Collection::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'image' => $faker->imageUrl(),
        'description' => $faker->paragraph,
        'time' => $faker->numberBetween(3600, 7200),
        'isPublish' => true,
    ];
});

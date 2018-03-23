<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'color' => array('ff4444', 'CC0000', 'ffbb33', 'ffbb33', '00C851', '007E33', '33b5e5', '0099CC')[array_rand(array('ff4444', 'CC0000', 'ffbb33', 'ffbb33', '00C851', '007E33', '33b5e5', '0099CC'))],
    ];
});

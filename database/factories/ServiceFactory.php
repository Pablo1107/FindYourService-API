<?php

use Faker\Generator as Faker;

$factory->define(App\Service::class, function (Faker $faker) {
    return [
        'title' => $faker->firstName.' Service',
        'description' => $faker->text,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'zipcode' => $faker->postcode,
        'longitude' => $faker->latitude,
        'latitude' => $faker->longitude,
    ];
});

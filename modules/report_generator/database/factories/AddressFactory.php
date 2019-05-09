<?php

/*
|--------------------------------------------------------------------------
| Address Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed Address table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Address::class, function (Faker\Generator $faker) {

    return [
        'line1' => $faker->streetName,
        'line2' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => $faker->postcode,
        'plus_four' => str_random(4),
        'country' => $faker->country,
	    'country_code' => $faker->countryCode,
    ];
});

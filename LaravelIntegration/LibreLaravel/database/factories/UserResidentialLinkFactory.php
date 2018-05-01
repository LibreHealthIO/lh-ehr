<?php

/*
|--------------------------------------------------------------------------
| User Residential Link Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed user_residential_links table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserResidentialLink::class, function (Faker\Generator $faker) {

    $user_ids = App\User::all()->pluck('id')->toArray();
    $addr_type = array(0, 1);
    return [
        'addressID' => factory(App\Address::class)->create()->id,
	'userID' => $faker->randomElement($user_ids),
	'type' => $addr_type[array_rand($addr_type, 1)],
    ];
});

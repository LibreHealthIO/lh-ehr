<?php

/*
|--------------------------------------------------------------------------
| User Facility Link Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed user_facility_links table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserFacilityLink::class, function (Faker\Generator $faker) {

    $user_ids = App\User::all()->pluck('id')->toArray();
    $facility_ids = App\Facility::all()->pluck('id')->toArray();
    $booleanValue = array(true, false);

    return [
	'userID' => $faker->randomElement($user_ids),
	'facilityId' => $faker->randomElement($facility_ids),
	'isDefault' => $booleanValue[array_rand($booleanValue, 1)],	
    ];
});

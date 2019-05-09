<?php

/*
|--------------------------------------------------------------------------
| User Setting Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed user_settings table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserSetting::class, function (Faker\Generator $faker) {

    $user_ids = App\User::all()->pluck('id')->toArray();

    return [
        'userID' => $faker->randomElement($user_ids),
	'setting_label' => str_random(10),
	'setting_value' => $faker->randomNumber($nbDigits = 2, $strict = false),
    ];
});

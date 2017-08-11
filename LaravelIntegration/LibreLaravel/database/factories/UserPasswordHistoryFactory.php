<?php

/*
|--------------------------------------------------------------------------
| User Password History Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for user_password_histories table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserPasswordHistory::class, function (Faker\Generator $faker) {

    $password_choice = array('secret', 'secret_1');
    $user_ids = App\User::all()->pluck('id')->toArray();

    return [
	'userID' => $faker->randomElement($user_ids),
	'password' => bcrypt($password_choice[array_rand($password_choice, 1)]),
	'last_update' => $faker->dateTime($max = 'now'),	
    ];
});

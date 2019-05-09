<?php

/*
|--------------------------------------------------------------------------
| User Secure Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed user_secures table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserSecure::class, function (Faker\Generator $faker) {
   
    $booleanValue = array(true, false); 
    static $password;

    return [
        'userID' => factory(App\User::class)->create()->id,
	'username' => $faker->userName,
	'password' => $password ?: $password = bcrypt('secret'),
	'active' => $booleanValue[array_rand($booleanValue, 1)],
	'authorized' => $booleanValue[array_rand($booleanValue, 1)],
	'pwd_expiration_date' => $faker->dateTime($max = 'now'),
	'remember_token' => str_random(10),
    ];
});

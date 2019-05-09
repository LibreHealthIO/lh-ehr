<?php

/*
|--------------------------------------------------------------------------
| User Communication Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed user_communications table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

use Faker\Factory as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserCommunication::class, function ($faker) {

    $user_ids = App\User::all()->pluck('id')->toArray();
    $type = array(0, 1, 2, 3, 4);

    $faker_phone = Faker::create('pt_BR');
    
    return [
	'userID' => $faker->randomElement($user_ids),
	'contact_number' => $faker_phone->cellphone,
	'type' => $type[array_rand($type, 1)],	
    ];
});

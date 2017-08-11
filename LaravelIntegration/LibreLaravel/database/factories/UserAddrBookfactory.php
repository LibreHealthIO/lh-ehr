<?php

/*
|--------------------------------------------------------------------------
| User Addr Book Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for user_addr_books table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserAddrBook::class, function (Faker\Generator $faker) {

    $user_ids = App\User::all()->pluck('id')->toArray();
    $abook_type = array('Imaging Service', 'Immunization Service', 'Lab Service', 'Specialist', 'Vendor', 'Distributor', 'Care Coordination', 'Other', 'EMR Direct', 'External Provider', 'External Organization');

    return [
	'userID' => $faker->randomElement($user_ids),
	'title' => $faker->title($gender = null|'male'|'female'),
	'email' => $faker->unique()->safeEmail,
	'url' => $faker->url,
	'assistant' => str_random(),
	'organization' => $faker->company,
	'valedictory' => str_random(10),
	'speciality' => str_random(10), /*These likes nurse, physician, etc*/
	'notes' => $faker->realText($maxNbChars = 200, $indexSize = 2),
	'abook_type' => $abook_type[array_rand($abook_type, 1)],
    ];
});

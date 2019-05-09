<?php

/*
|--------------------------------------------------------------------------
| User Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for users table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/


use Faker\Factory as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function ($faker) {

    $middle_name = array('kumar', 'singh', 'stewart'); //Random middle names.
    $calendar_ui = array('outlook', 'Original', 'Fancy');
    $newcrop_user_role = array('NewCrop Admin', 'NewCrop Nurse', 'NewCrop Manager', 'NewCrop Dcotor', 'NewCrop Midlevel Prescriber', 'NewCrop Supervising Doctor');
    $access_control = array('Accounting', 'Administrators', 'Clinicians', 'Emergency Log', 'Front Office', 'Physicians'); /*Update if new acl added.*/	
    $booleanVal = array(true, false);

    $faker_tax = Faker::create('it_IT');
    $faker_npi = Faker::create('ar_SA');
    return [
	'fname' => $faker->firstName($gender = null|'male'|'female'),
	'mname' => $middle_name[array_rand($middle_name, 1)],
	'lname' => $faker->lastName,
	'federalTaxId' => $faker_tax->unique()->taxId(),
	'federalDrugId' => $faker->uuid,
	'see_auth' => $faker->randomDigit,
	'npi' => $faker_npi->nationalIdNumber,
	'suffix' => $faker->suffix,
	'taxonomy' => str_random(10),
	'calendar_ui' => $calendar_ui[array_rand($calendar_ui, 1)],
	'info' => $faker->realText($maxNbChars = 200, $indexSize = 2),
	'newcrop_user_role' => $newcrop_user_role[array_rand($newcrop_user_role, 1)],
	'access_control' => $access_control[array_rand($access_control, 1)],
	'inCalendar' => $booleanVal[array_rand($booleanVal, 1)],
    ];
});

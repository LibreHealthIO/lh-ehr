<?php

/*
|--------------------------------------------------------------------------
| Patient Data Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_face_sheets table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientFaceSheet::class, function (Faker\Generator $faker) {

    $sex = array('male', 'female'); //Include other options here.
    $marietal_status = array('married', 'single', 'divorced', 'widowed', 'seperated', 'd_partner');
    $middle_name = array('kumar', 'singh', 'stewart'); //Random middle names.

    return [
	'f_name' => $faker->firstName($gender = null | 'male' | 'female'),
	'm_name' => $middle_name[array_rand($middle_name, 1)],
	'l_name' => $faker->lastName,
	'DOB' => $faker->date($format = 'Y-m-d', $max = 'now'),
	'marietal_status' => $marietal_status[array_rand($marietal_status, 1)],
	'license_id' => $faker->uuid,
	'email' => $faker->unique()->safeEmail,
	'sex' => $sex[array_rand($sex, 1)],
	'billing_note' => $faker->text($maxNbChars = 200),	
        'pid' => factory(App\PatientData::class)->create()->pid,
    ];
});

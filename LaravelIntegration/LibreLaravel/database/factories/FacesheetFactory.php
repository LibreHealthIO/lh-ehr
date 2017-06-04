<?php

/*
|--------------------------------------------------------------------------
|  Facesheet Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed patient_face_sheets table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientFaceSheet::class, function (Faker\Generator $faker) {

    $gender = array('male', 'female');
    $marietal_status = array('married', 'single', 'divorced', 'widowed', 'seperated', 'domestic partner');
    return [
        'f_name' => $faker->firstName,
        'm_name' => str_random(12),
        'l_name' => $faker->lastName,
        'DOB' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'marietal_status' => $marietal_status[array_rand($marietal_status, 1)],
        'license_id' => str_random(12),
        'email' => $faker->unique()->safeEmail,
	'sex' => $gender[array_rand($gender, 1)],
	'billing_note' => $faker->text($maxNbChars = 200),
    ];
});

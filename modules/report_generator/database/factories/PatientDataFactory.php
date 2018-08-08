<?php

/*
|--------------------------------------------------------------------------
| Patient Data Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_datas table.
| Further used in PatientDataTableSeeder.phpr.php.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientData::class, function (Faker\Generator $faker) {

    $occupation = array('Engineer', 'Doctor', 'Lawyer', 'Site Worker', 'Student'); //Add if any new occupation is added in UI.
    $industry = array('Law Firm', 'Engineering Firm', 'Construction Firm', 'College'); //Add if any new industry is added in UI
    return [
        'pid' => $faker->unique()->randomNumber($nbDigits = 4, $strict = false),
        'title' => $faker->title($gender = null|'male'|'female'),
        'occupation' => $occupation[array_rand($occupation, 1)],
	'industry' => $industry[array_rand($industry, 1)],
	'addressId' => factory(App\Address::class)->create()->id,
    ];
});

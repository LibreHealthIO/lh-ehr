<?php

/*
|--------------------------------------------------------------------------
| Patient Contact Link Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_contact_links table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientContactLink::class, function (Faker\Generator $faker) {

    //Get all the patient_ids pids in patient_datas table.
    $pids = App\PatientData::all()->pluck('pid')->toArray();

    return [
        'pid' => $faker->randomElement($pids),
	'contactsId' => factory(App\PatientContact::class)->create()->id,
    ];
});

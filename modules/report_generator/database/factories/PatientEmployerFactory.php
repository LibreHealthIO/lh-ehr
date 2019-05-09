<?php

/*
|--------------------------------------------------------------------------
| Patient Employer Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_employers table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientEmployer::class, function (Faker\Generator $faker) {

    //Get all the patient_ids pids in patient_datas table.
    $pids = App\PatientData::all()->pluck('pid')->toArray(); 

    return [	
        'pid' => $faker->randomElement($pids),
	'addressId' => factory(App\Address::class)->create()->id,
	'name' => $faker->company,
    ];
});

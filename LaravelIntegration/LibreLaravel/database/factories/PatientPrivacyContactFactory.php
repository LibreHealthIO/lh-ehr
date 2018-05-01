<?php

/*
|--------------------------------------------------------------------------
| Patient Privacy Contacts Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_contact_communications table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientPrivacyContact::class, function (Faker\Generator $faker) {

    //Get all the patient_ids pids in patient_datas table.
    $pids = App\PatientData::all()->pluck('pid')->toArray();

    $booleanValue = array(true, false);

    return [
	'pid' => $faker->randomElement($pids),
	'contactId' => factory(App\PatientContact::class)->create()->id,
	'allow_patient_portal' => $booleanValue[array_rand($booleanValue, 1)],
	'allow_health_info_ex' => $booleanValue[array_rand($booleanValue, 1)],
	'allow_imm_info_share' => $booleanValue[array_rand($booleanValue, 1)],
	'allow_imm_reg_use' => $booleanValue[array_rand($booleanValue, 1)],
	'vfc' => $booleanValue[array_rand($booleanValue, 1)],
	'secure_email' => $faker->unique()->safeEmail,
	'deceased_reason' => $faker->text($maxNbChars = 100),
	'deceased_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});

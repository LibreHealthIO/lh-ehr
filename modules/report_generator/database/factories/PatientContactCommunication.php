<?php

/*
|--------------------------------------------------------------------------
| Patient Contact Communication Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_contact_communications table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientContactCommunication::class, function (Faker\Generator $faker) {

    //Get all the patient_ids pids in patient_datas table.
    $pids = App\PatientData::all()->pluck('pid')->toArray();

    $mailMode = array(true, false);
    $voiceMode = array(true, false);
    $messageMode = array(true, false);

    return [
	'pid' => $faker->randomElement($pids),
	'contactId' => factory(App\PatientContact::class)->create()->id,
	'mailMode' => $mailMode[array_rand($mailMode, 1)],
	'voiceMode' => $voiceMode[array_rand($voiceMode, 1)],
	'message' => $faker->text($maxNbChars = 200),
	'messageMode' => $messageMode[array_rand($messageMode, 1)],
    ];
});

<?php

/*
|--------------------------------------------------------------------------
| Patient Contact Factory
|--------------------------------------------------------------------------
|
| Use this to generate fake data for patient_contacts table. 
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientContact::class, function (Faker\Generator $faker) {

    /*
     * ProviderId and RefereneProviderId may refer to users table.
     * If this is the case, then get id from user factory. 
     * Right now they are seeded with random numbers.
     */ 

    $contact_relationship = array('father', 'mother', 'spouse', 'siblings', 'son', 'daughter', 'freind', 'self'); //Add any other available option here.
    return [
	'providerId' => $faker->randomNumber($nbDigits = 4, $strict = false),
	'refProviderId' => $faker->randomNumber($nbDigits = 4, $strict = false),
	'home_phone' => $faker->e164PhoneNumber,
	'work_phone' => $faker->e164PhoneNumber,
	'contact_phone' => $faker->e164PhoneNumber,
	'contact_relationship' => $contact_relationship[array_rand($contact_relationship, 1)],
	'patient_email' => $faker->unique()->safeEmail,
	'county' => str_random(10),
	'country_code' => $faker->countryCode,	
    ];
});

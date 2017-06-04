<?php

/*
|--------------------------------------------------------------------------
| Patient Social Statistics Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed patient_social_statistics table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\PatientSocialStatistics::class, function (Faker\Generator $faker) {

    $migrantSeasonal = array('migrant', 'seasonal');
    return [
        'ethnicity' => str_random(10),
        'religion' => str_random(10),
        'interpreter' => str_random(10),
        'migrant_seasonal' => $migrantSeasonal[array_rand($migrantSeasonal, 1)],
        'family_size' => rand(0, 20),
        'monthly_income' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = NULL),
        'homeless' => $faker->country,
	'financial_review' => $faker->dateTime($max  = 'now', $timezone = date_default_timezone_get()),
	'language' => str_random(10),
    ];
});

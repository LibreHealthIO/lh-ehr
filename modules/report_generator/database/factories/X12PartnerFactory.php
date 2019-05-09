<?php

/*
|--------------------------------------------------------------------------
| X12 Partner Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed X12 Partners table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\X12Partner::class, function (Faker\Generator $faker) {

    $version = array('005010X222A1', '004010X098A1');
    $processing_format = array('standard', 'medi-cal', 'cms', 'proxymed');
    $ISA15 = array('T', 'P');
    $boolean_value = array(true, false);
    $ISA07_05 = array('01', '14', '20', '27', '28', '29', '30', '33', 'ZZ');
    $var = array('00', '03');
    $id_number = '830682610';

    return [
        'name' => $faker->company." ".$faker->companySuffix,
	'id_number' => $id_number,
	'x12_sender_id' => str_random(10),
	'x12_receiver_id' => str_random(10),
	'x12_version' => $version[array_rand($version, 1)],
	'processing_format' => $processing_format[array_rand($processing_format, 1)],
	'x12_isa_01' => '00',
	'x12_isa_02' => NULL,
	'x12_isa_03' => '00',
	'x12_isa_04' => NULL,
	'x12_isa_05' => $ISA07_05[array_rand($ISA07_05, 1)],
	'x12_isa_07' => $ISA07_05[array_rand($ISA07_05, 1)],
	'x12_isa_14' => $boolean_value[array_rand($boolean_value, 1)],
	'x12_isa_15' => $ISA15[array_rand($ISA15, 1)],
	'x12_gs_02' => 'AV0'.str_random(10),
	'x12_per_06' => 'P'.str_random(10),
	'x12_gs_03' => str_random(10),
    ];
});

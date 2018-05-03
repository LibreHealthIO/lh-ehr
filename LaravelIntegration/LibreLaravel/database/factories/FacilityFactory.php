<?php

/*
|--------------------------------------------------------------------------
| Facility Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed facilities table and test database.
| @author Priyanshu Sinha <pksinha217@gmail.com>
|
*/

use Faker\Factory as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Facility::class, function ($faker) {

    $booleanValue = array(true, false);
    $taxIdType = array('EI', 'SS');

    /*Different language formatters*/
    $faker = Faker::create('fr_BE');
    $faker_phone = Faker::create('pt_BR');
    $faker_fax = Faker::create('en_HK');

    return [
        'addressID' => factory(App\Address::class)->create()->id,
        'name' => str_random(10),
        'federal_ein' => $faker->vat,
        'phone' => $faker_phone->cellphone,
        'fax' => $faker_fax->faxNumber,
        'website' => $faker->url,
        'email' => $faker->unique()->safeEmail,
        'service_location' => $booleanValue[array_rand($booleanValue, 1)],
        'billing_location' => $booleanValue[array_rand($booleanValue, 1)],
        'accept_assignment' => $booleanValue[array_rand($booleanValue, 1)],
        'pos_code' => $faker->randomNumber($nbDigits = 3, $strict = false),
        'attn' => str_random(10),
        'domain_identifier' => str_random(10),
        'facility_npi' => $faker->uuid,
        'tax_id_type' => $taxIdType[array_rand($taxIdType, 1)],
        'color' => $faker->hexcolor,
        'primary_business_entity' => $booleanValue[array_rand($booleanValue, 1)],
    ];
});

<?php
/*
|--------------------------------------------------------------------------
| DraggableComponent Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed DraggableComponent table and test database.
| Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
|
*/

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {

$booleanValue = array(true, false);

    return [
        'option_id' => str_random(10)->unique(),
        'is_default' => $booleanValue[array_rand($booleanValue, 1)],
        'user' => $faker->name,
        'title' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'order' => $faker->number(2),
        'active' => $booleanValue[array_rand($booleanValue, 1)],
        'notes' => $faker->words($nb = 3, $asText = false),
        'toggle_sort' => $booleanValue[array_rand($booleanValue, 1)],
        'toggle_display' => $booleanValue[array_rand($booleanValue, 1)],
    ];
});

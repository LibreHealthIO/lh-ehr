<?php
/*
|--------------------------------------------------------------------------
| Report Format Factory
|--------------------------------------------------------------------------
|
| Use this factory to seed Report Format table and test database.
| Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
|
*/

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {

    // Get all draggable_components_ids in draggable_components table
    $draggable_components_ids = Entities\DraggableComponent::all()->pluck('id')->toArray();

    return [
        'user' => $faker->name,
        'title' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'system_feature' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'description' => $faker->text($maxNbChars = 100),
        'draggable_components_id' => $faker->randomElement($draggable_components_ids),
    ];
});

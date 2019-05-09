<?php

/* Use this to seed draggable_components table.
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */

namespace Modules\ReportGenerator\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DraggableComponentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory("../../". Entities\DraggableComponent::class, 25)->create();
        // $this->call("OthersTableSeeder");
    }
}

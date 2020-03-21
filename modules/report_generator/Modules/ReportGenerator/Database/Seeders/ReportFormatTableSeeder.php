<?php

/* Use this to seed report_formats table.
 * Copyright 2018 Tigpezeghe Rodrige K. <tigrodrige@gmail.com>
 * */

//namespace Modules\ReportGenerator\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ReportFormatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(Modules\ReportGenerator\Entities\ReportFormat::class, 25)->create();
        // $this->call("OthersTableSeeder");
    }
}

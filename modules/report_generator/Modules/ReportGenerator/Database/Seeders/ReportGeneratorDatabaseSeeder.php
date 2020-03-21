<?php

namespace Modules\ReportGenerator\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ReportGeneratorDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //$this->call(DraggableComponentTableSeeder::class);
        //$this->call(ReportFormatTableSeeder::class);

        // Seed the database with default system features and report formats.
        $path = './../../../../Documentation/librereportgenerator.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Report generator tables seeded!')
    }
}

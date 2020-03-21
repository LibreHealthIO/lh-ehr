<?php

use Illuminate\Database\Seeder;

class PatientEmployerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed patient_employers table.
     * @return void
     */
    public function run()
    {
       	factory(App\PatientEmployer::class, 25)->create();
    }
}

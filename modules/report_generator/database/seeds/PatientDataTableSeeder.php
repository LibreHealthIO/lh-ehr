<?php

use Illuminate\Database\Seeder;

class PatientDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeds patient_datas table only.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\PatientData::class, 25)->create();
    }
}

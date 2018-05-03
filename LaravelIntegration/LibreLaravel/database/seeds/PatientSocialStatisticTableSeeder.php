<?php

use Illuminate\Database\Seeder;

class PatientSocialStatisticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed patient_social_statistics table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\PatientSocialStatistic::class, 25)->create();
    }
}

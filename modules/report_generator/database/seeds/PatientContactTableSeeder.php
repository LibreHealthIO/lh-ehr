<?php

use Illuminate\Database\Seeder;

class PatientContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed patient_contact_links table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\PatientContactLink::class, 25)->create();
    }
}

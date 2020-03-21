<?php

use Illuminate\Database\Seeder;

class PatientContactCommunicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed patient_contact_communications table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\PatientContactCommunication::class, 25)->create();
    }
}

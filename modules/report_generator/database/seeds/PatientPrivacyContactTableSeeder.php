<?php

use Illuminate\Database\Seeder;

class PatientPrivacyContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed patient_privacy_contacts table.
     * @author Piyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\PatientPrivacyContact::class, 25)->create();
    }
}

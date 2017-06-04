<?php

use Illuminate\Database\Seeder;

class FacesheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed patient_face_sheets table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */

    public function run()
    {
        factory(App\PatientFaceSheet::class, 25)->create();
    }
}

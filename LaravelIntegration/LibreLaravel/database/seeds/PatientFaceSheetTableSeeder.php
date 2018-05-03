<?php

use Illuminate\Database\Seeder;

class PatientFaceSheetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder for patient_face_sheets table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\PatientFaceSheet::class, 25)->create();
    }
}

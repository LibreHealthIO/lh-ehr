<?php

use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed patient_contacts table.
     * This can be seeded standalone as contacts are generic.
     * @author Priyanshu Sinha<pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
       factory(App\PatientContact::class, 25)->create(); 
    }
}

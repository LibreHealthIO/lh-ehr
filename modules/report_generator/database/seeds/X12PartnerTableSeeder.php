<?php

use Illuminate\Database\Seeder;

class X12PartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed x12_partners table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\X12Partner::class, 25)->create();
    }
}

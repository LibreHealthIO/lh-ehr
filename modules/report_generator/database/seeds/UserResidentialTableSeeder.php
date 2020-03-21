<?php

use Illuminate\Database\Seeder;

class UserResidentialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed user_residential_links table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\UserResidentialLink::class, 25)->create(); 
    }
}

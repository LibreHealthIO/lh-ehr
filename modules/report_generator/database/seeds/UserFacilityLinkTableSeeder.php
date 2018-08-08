<?php

use Illuminate\Database\Seeder;

class UserFacilityLinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeds user_facility_links table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\UserFacilityLink::class, 25)->create();
    }
}

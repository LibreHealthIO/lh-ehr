<?php

use Illuminate\Database\Seeder;

class UserSecureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeds user_secures table.
     * @return void
     */
    public function run()
    {
        factory(App\UserSecure::class, 25)->create();
    }
}

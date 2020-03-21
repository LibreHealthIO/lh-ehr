<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates seeder for users table.
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 25)->create();
    }
}

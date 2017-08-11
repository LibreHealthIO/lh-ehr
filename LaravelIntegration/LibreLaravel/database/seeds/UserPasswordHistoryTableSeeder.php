<?php

use Illuminate\Database\Seeder;

class UserPasswordHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This creates seeder for user_passowrd_histories table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\UserPasswordHistory::class, 25)->create();
    }
}

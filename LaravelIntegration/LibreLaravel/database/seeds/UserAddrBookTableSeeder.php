<?php

use Illuminate\Database\Seeder;

class UserAddrBookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use this to seed user_addr_books table.
     * @author Priyanshu Sinha <pksinha217@gmail.com>
     * @return void
     */
    public function run()
    {
        factory(App\UserAddrBook::class, 25)->create();
    }
}

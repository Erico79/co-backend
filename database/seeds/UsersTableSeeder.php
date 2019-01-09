<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create system admin
        User::create([
            'first_name' => 'Eric',
            'last_name' => 'Murimi',
            'email' => env('ADMIN_EMAIL'),
            'mobile_phone' => env('ADMIN_PHONE_NO'),
            'password' => bcrypt(env('ADMIN_PASS'))
        ]);
    }
}

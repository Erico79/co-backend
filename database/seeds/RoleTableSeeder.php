<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'System Administrator',
                'code' => Role::SYSADMIN,
            ],
            [
                'name' => 'Group Administrator',
                'code' => Role::GROUPADMIN,
            ],
            [
                'name' => 'Group Member',
                'code' => Role::GROUPMEMBER,
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'city_id' => 1,
                'name' => 'john',
                'email' => 'admin@larashop.com',
                'nik' => '3173765897654',
                'username' => 'administrator',
                'phone' => '0812345678',
                'address' => 'Jl.in aja',
                'role' => 'ADMIN',
                'status' => 'ACTIVE',
                'avatar' => null,
                'password' => Hash::make('aaaaa')
            ]
        );

        User::create([
            'city_id' => 2,
            'name' => 'doe',
            'email' => 'staff@larashop.com',
            'nik' => '3173765897655',
            'username' => 'staff_doe',
            'phone' => '0812345677',
            'address' => 'Jl.in dulu',
            'role' => 'STAFF',
            'status' => 'ACTIVE',
            'avatar' => null,
            'password' => Hash::make('aaaaa'),
            'created_by' => 1,
        ]);

        User::create([
            'city_id' => 3,
            'name' => 'Jeffrey way',
            'email' => 'customer@larashop.com',
            'username' => 'customer_jw',
            'phone' => '0812345676',
            'address' => 'Jl.in dulu aja',
            'role' => 'CUSTOMER',
            'status' => 'INACTIVE',
            'avatar' => null,
            'password' => Hash::make('aaaaa')
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
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
        DB::table('users')->insert([
            [
                'first_name' => 'Super Admin',
                'username' => 'superadmin',
                'phone_number' => '7894561235',
                'email' => 'superadmin@admin.com',
                'default_password' => Hash::make('ERP$oftware@2023#'),
                'password' => Hash::make('password'),
                'first_login' => now()
            ],
            [
                'first_name' => 'Technical Person',
                'username' => 'technicalperson',
                'phone_number' => '6894561230',
                'email' => 'technicalperson@admin.com',
                'default_password' => Hash::make('ERP$oftware@2023#'),
                'password' => Hash::make('password'),
                'first_login' => now()
            ],
            [
                'first_name' => 'Admin',
                'username' => 'admin',
                'phone_number' => '5894561230',
                'email' => 'admin@admin.com',
                'default_password' => Hash::make('ERP$oftware@2023#'),
                'password' => Hash::make('password'),
                'first_login' => now()
            ],
        ]);
    }
}

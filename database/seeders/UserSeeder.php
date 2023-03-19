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
                'first_name' => 'Admin',
                'username' => 'admin',
                'phone_number' => '9800000000',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'first_login' => now()
            ],
            [
                'first_name' => 'Project Manager',
                'username' => 'projectmanager',
                'phone_number' => '9800000000',
                'email' => 'projectmanager@admin.com',
                'password' => Hash::make('password'),
                'first_login' => now()
            ],
            [
                'first_name' => 'User',
                'username' => 'user',
                'phone_number' => '9800000000',
                'email' => 'user@admin.com',
                'password' => Hash::make('password'),
                'first_login' => now()
            ],
        ]);
    }
}

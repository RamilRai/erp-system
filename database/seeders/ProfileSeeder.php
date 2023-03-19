<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            ['user_id' => '1', 'first_name' => 'Admin', 'email' => 'admin@admin.com'],
            ['user_id' => '2', 'first_name' => 'Project Manager', 'email' => 'projectmanager@admin.com'],
            ['user_id' => '3', 'first_name' => 'User', 'email' => 'user@admin.com']
        ]);
    }
}

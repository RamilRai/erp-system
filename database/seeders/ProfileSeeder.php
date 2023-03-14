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
            ['user_id' => '1', 'first_name' => 'Super Admin', 'email' => 'superadmin@admin.com'],
            ['user_id' => '2', 'first_name' => 'Technical Person', 'email' => 'technicalperson@admin.com'],
            ['user_id' => '3', 'first_name' => 'Admin', 'email' => 'admin@admin.com']
        ]);
    }
}

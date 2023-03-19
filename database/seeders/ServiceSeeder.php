<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            ['service_type' => 'Website Devlopment'],
            ['service_type' => 'Web Application'],
            ['service_type' => 'Graphics Design'],
            ['service_type' => 'Digital Marketing']
        ]);
    }
}

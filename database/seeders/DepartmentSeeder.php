<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            ['department_name' => 'Administrative'],
            ['department_name' => 'Back-end'],
            ['department_name' => 'Front-end'],
            ['department_name' => 'Graphics'],
            ['department_name' => 'Support'],
            ['department_name' => 'Finance']
        ]);
    }
}

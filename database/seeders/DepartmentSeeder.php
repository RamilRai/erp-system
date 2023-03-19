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
            ['department_name' => 'Finance'],
            ['department_name' => 'Human Resource'],
            ['department_name' => 'Customer Support'],
            ['department_name' => 'Marketing & Sales'],
            ['department_name' => 'Backend'],
            ['department_name' => 'Frontend'],
            ['department_name' => 'UI/UX & Graphics'],
            ['department_name' => 'Mobile App'],
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Department extends Model
{
    use HasFactory;

    public static function storeDepartment($post)
    {
        try {
            $freshData = sanitizeData($post);
            $department = new Department;
            $department->department_name = Str::title($freshData['department_name']);
            $result = $department->save();

            if($result){
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}

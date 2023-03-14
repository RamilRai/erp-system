<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    public static function storeUserRole($user)
    {
        try {
            $userRole = new UserRole;
            $userRole->user_id = $user->id;
            $userRole->role_id = 3;
            $result = $userRole->save();

            if(!$result){
                throw new Exception("Error Processing Request", 1);
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

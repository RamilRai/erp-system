<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Models\Department;
use Exception;
use Str;

class Profile extends Model
{
    use HasFactory;

    public function departments()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public static function storeProfile($post, $profile)
    {
        try {
            Arr::forget($post, 'documents');
            $freshData = sanitizeData($post);
            $profile->first_name = Str::title($freshData['first_name']);
            $profile->middle_name = Str::title($freshData['middle_name']);
            $profile->last_name = Str::title($freshData['last_name']);
            $profile->permanent_address = Str::title($freshData['permanent_address']);
            $profile->temporary_address = Str::title($freshData['temporary_address']);
            $profile->phone_number = $freshData['phone_number'];
            $profile->email = $freshData['email'];
            if ($post['updateProfile'] == 'N') {
                $profile->gender = $freshData['gender'];
                $profile->dob_bs = $freshData['dob_bs'];
                $profile->dob_ad = $freshData['dob_ad'];
                $profile->blood_group = $freshData['blood_group'];
                $profile->recruited_date_bs = $freshData['recruited_date_bs'];
                $profile->recruited_date_ad = $freshData['recruited_date_ad'];
                $profile->department_id = $freshData['department_id'];
            }
            $result = $profile->save();

            if(!$result){
                throw new Exception("Error Processing Request", 1);
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

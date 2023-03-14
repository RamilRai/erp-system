<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Str;

class Profile extends Model
{
    use HasFactory;

    public static function storeProfile($post, $profile)
    {
        try {
            $freshData = sanitizeData($post);
            $profile->first_name = Str::title($post['first_name']);
            $profile->middle_name = Str::title($post['middle_name']);
            $profile->last_name = Str::title($post['last_name']);
            $profile->permanent_address = Str::title($post['permanent_address']);
            $profile->temporary_address = Str::title($post['temporary_address']);
            $profile->phone_number = $post['phone_number'];
            $profile->email = $post['email'];
            if ($post['updateProfile'] == 'N') {
                $profile->gender = $post['gender'];
                $profile->dob_bs = $post['dob_bs'];
                $profile->dob_ad = $post['dob_ad'];
                $profile->blood_group = $post['blood_group'];
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

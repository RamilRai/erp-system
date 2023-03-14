<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Service extends Model
{
    use HasFactory;

    public static function storeService($post)
    {
        try {
            $freshData = sanitizeData($post);
            $service = new Service;
            $service->service_type = Str::title($freshData['service_type']);
            $result = $service->save();

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

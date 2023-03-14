<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    public static function getJsonData($type, $message, $response)
    {
        echo json_encode(['type' => $type, 'message' => $message, 'response' => $response]);
        exit;
    }
}

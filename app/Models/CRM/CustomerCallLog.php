<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use DB;
use Exception;

class CustomerCallLog extends Model
{
    use HasFactory;

    public static function storeCallLog($post)
    {
        try {
            $freshData = sanitizeData($post);
            $callLog = $post['id'] == null ? new CustomerCallLog : CustomerCallLog::where('id', $post['id'])->first();
            $callLog->customer_id = $post['customer_id'];
            $callLog->call_date = $post['call_date'];
            $callLog->called_by = Str::title($post['called_by']);
            $callLog->received_by = Str::title($post['received_by']);
            $callLog->remarks = $post['remarks'];
            $result = $callLog->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchCallLogInfo()
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " status = 'Y'";

            if ($get['sSearch_2']) {
                $cond .= "and lower(called_by) like'%".$get['sSearch_2']."%'";
            }

            if ($get['sSearch_3']) {
                $cond .= "and lower(received_by) like'%".$get['sSearch_3']."%'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }
            $sql = "SELECT
                        COUNT(*) OVER() AS totalrecs,
                        id,
                        call_date,
                        called_by,
                        received_by
                    FROM
                        customer_call_logs
                    WHERE $cond AND customer_id = '".$get['customer_id']."'
                    ORDER BY id
                    DESC";

            if ($limit > -1) {
                $sql = $sql . ' limit ' . $limit . ' offset ' . $offset . '';
            }

            $result = DB::select($sql);

            $ndata = $result;
            $ndata['totalrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            $ndata['totalfilteredrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;

            return $ndata;

        } catch (Exception $e){
            return [];
        }
    }
}

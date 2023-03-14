<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Str;
use DB;

class Lead extends Model
{
    use HasFactory;

    public static function storeLead($post)
    {
        try {
            $freshData = sanitizeData($post);
            $lead = $freshData['id'] == null ? new Lead : Lead::where('id', $freshData['id'])->first();
            $lead->organization_type = $freshData['organization_type'];
            $lead->organization_name = Str::title($freshData['organization_name']);
            $lead->address = Str::title($freshData['address']);
            $lead->email = $freshData['email'];
            $lead->contact_number = $freshData['contact_number'];
            $lead->mobile_number = $freshData['mobile_number'];
            $lead->lead_by_name = Str::title($freshData['lead_by_name']);
            $lead->lead_date = $freshData['lead_date'];
            $lead->lead_status = $freshData['lead_status'];
            $result = $lead->save();

            if(!$result){
                throw new Exception("Error Processing Request", 1);
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchLeadInfo()
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " active_status = 'Y'";

            if ($get['sSearch_1']) {
                $cond .= "and lower(organization_type) like'%".$get['sSearch_1']."%'";
            }

            if ($get['sSearch_2']) {
                $cond .= "and lower(organization_name) like'%".$get['sSearch_2']."%'";
            }

            if ($get['sSearch_4']) {
                $cond .= "and lower(lead_by_name) like'%".$get['sSearch_4']."%'";
            }

            if ($get['sSearch_5']) {
                $cond .= "and lower(lead_status) like'%".$get['sSearch_5']."%'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }
            $sql = "SELECT
                        COUNT(*) OVER() AS totalrecs,
                        id,
                        organization_type,
                        organization_name,
                        address,
                        lead_by_name,
                        lead_status
                    FROM
                        leads
                    WHERE $cond
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

    public static function deleteLeadData($post)
    {
        try {
            $data = Lead::where(['id'=>$post['id']])->update(['active_status'=>'N']);

            if($data){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function viewLeadData($post)
    {
        $data = Lead::where('id', $post['id'])->first();
        return $data;
    }

    public static function changeLeadStatus($post)
    {
        try {
            $data = Lead::where(['id'=>$post['id']])->update(['lead_status'=>$post['value']]);

            if($data){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}

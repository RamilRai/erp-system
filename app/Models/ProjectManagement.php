<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;
use App\Models\CRM\Customer;
use Illuminate\Support\Arr;
use Exception;
use Str;

class ProjectManagement extends Model
{
    use HasFactory;

    protected $table = 'project_management';

    public function profiles()
    {
        return $this->belongsTo(Profile::class, 'project_lead_by', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public static function storeData($post, $projectManagement)
    {
        try {
            $projectManagement->assign_team_members = json_encode($post['assign_team_members']);
            Arr::forget($post, 'assign_team_members'); // to remove a given key value pair from an array
            $freshData = sanitizeData($post);
            $projectManagement->project_name = Str::title($freshData['project_name']);
            $projectManagement->project_type = $freshData['project_type'];
            if ($freshData['project_type'] == 'Client') {
                $projectManagement->customer_id = Str::title($freshData['customer_id']);
            }
            $projectManagement->project_time_duration = $freshData['project_time_duration'];
            $projectManagement->start_date_bs = $freshData['start_date_bs'];
            $projectManagement->start_date_ad = $freshData['start_date_ad'];
            $projectManagement->end_date_bs = $freshData['end_date_bs'];
            $projectManagement->end_date_ad = $freshData['end_date_ad'];
            $projectManagement->project_lead_by = $freshData['project_lead_by'];
            $projectManagement->project_status = $freshData['project_status'];
            $result = $projectManagement->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchProjectManagementInfo()
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " PM.status = 'Y'";

            if ($get['sSearch_1']) {
                $cond .= "and lower(PM.project_name) like'%".$get['sSearch_1']."%'";
            }

            if ($get['sSearch_2']) {
                $cond .= "and lower(PM.project_type) like'%".$get['sSearch_2']."%'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }
            $sql = "SELECT
                        COUNT(*) OVER() AS totalrecs,
                        PM.id,
                        PM.project_name,
                        PM.project_type,
                        PM.start_date_bs,
                        PM.end_date_bs,
                        PM.assign_team_members,
                        PM.project_status,
                        P.profile,
                        P.first_name,
                        P.middle_name,
                        P.last_name
                    FROM
                        project_management AS PM
                    JOIN
                        profiles as P ON PM.project_lead_by = P.user_id
                    WHERE $cond
                    ORDER BY id
                    DESC";

            if ($limit > -1) {
                $sql = $sql . ' limit ' . $limit . ' offset ' . $offset . '';
            }

            $result = \DB::select($sql);

            $ndata = $result;
            $ndata['totalrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            $ndata['totalfilteredrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            return $ndata;

        } catch (Exception $e){
            return [];
        }
    }
}

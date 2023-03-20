<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectManagement;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCompletedMail;
use Illuminate\Support\Arr;
use App\Jobs\VerifiedMail;
use Carbon\Carbon;
use Str;
use DB;

class TaskManagement extends Model
{
    use HasFactory;

    protected $table = 'task_management';

    public function projects()
    {
        return $this->belongsTo(ProjectManagement::class, 'project_id', 'id');
    }

    public static function storeData($post, $taskManagement)
    {
        try {
            $taskManagement->assigned_to = json_encode($post['assigned_to']);
            Arr::forget($post, 'assigned_to'); // to remove a given key value pair from an array
            $freshData = sanitizeData($post);
            $taskManagement->task_title = Str::title($freshData['task_title']);
            if(isset($freshData['project_id'])){
                $taskManagement->project_id = $freshData['project_id'];
            }
            $taskManagement->task_type = $freshData['task_type'];
            $taskManagement->task_start_date_bs = $freshData['task_start_date_bs'];
            $taskManagement->task_start_date_ad = $freshData['task_start_date_ad'];
            $taskManagement->task_end_date_bs = $freshData['task_end_date_bs'];
            $taskManagement->task_end_date_ad = $freshData['task_end_date_ad'];
            $taskManagement->estimated_hour = $freshData['estimated_hour'];
            $taskManagement->priority = $freshData['priority'];
            $taskManagement->task_description = $freshData['task_description'];
            $taskManagement->task_point = $freshData['task_point'];
            if ($freshData['id'] == null) {
                $taskManagement->task_status = 'Not Started Yet';
            }
            $result = $taskManagement->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchTaskManagementInfo($userID)
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " TM.status = 'Y'";

            if(!empty($userID)){
                $userJson = '"'.$userID.'"';
                $cond .= " AND project_lead_by = ".$userID." OR JSON_CONTAINS(assigned_to, '[".$userJson."]', '$') ";
            }

            if ($get['sSearch_2']) {
                $cond .= "and lower(TM.project_type) like'%".$get['sSearch_2']."%'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }
            $sql = "SELECT
                        COUNT(*) OVER() AS totalrecs,
                        TM.id,
                        PM.id as projectid,
                        PM.project_lead_by,
                        TM.ticket_number,
                        TM.task_title,
                        PM.project_name,
                        TM.task_type,
                        TM.task_start_date_bs,
                        TM.task_end_date_bs,
                        TM.estimated_hour,
                        TM.priority,
                        TM.task_status,
                        TM.assigned_to
                    FROM
                        task_management AS TM
                    JOIN
                        project_management AS PM ON TM.project_id = PM.id
                    WHERE $cond
                    ORDER BY id
                    DESC";

            if ($limit > -1) {
                $sql = $sql . ' limit ' . $limit . ' offset ' . $offset . '';
            }
            // echo $sql;exit;
            $result = \DB::select($sql);

            $ndata = $result;
            $ndata['totalrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            $ndata['totalfilteredrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            return $ndata;

        } catch (Exception $e){
            return [];
        }
    }

    public static function storeDocuments($post, $taskManagement)
    {
        try {
            $currentDateTime = Carbon::now();
            $taskManagement->task_completed_date_and_time_ad = $currentDateTime->format('Y-m-d H:i:s');
            $taskManagement->task_status = 'Completed';
            $taskManagement->feedback = $post['feedback'];

            // for completed status
            $currentDate = Carbon::parse(date('Y-m-d'));
            $toBeCompletedDate = TaskManagement::select('task_end_date_ad')->where('id', $post['id'])->first();
            $toBeCompletedDate = Carbon::parse($toBeCompletedDate->task_end_date_ad);

            if ($toBeCompletedDate >= $currentDate) {
                $differenceInDays = $currentDate->diffInDays($toBeCompletedDate);
            } else {
                $differenceInDays = -$currentDate->diffInDays($toBeCompletedDate);
            }

            if ($differenceInDays > 0) {
                $taskManagement->completed_status = "Before Deadline ($differenceInDays days)";
            } elseif ($differenceInDays < 0) {
                $taskManagement->completed_status = "After Deadline ($differenceInDays days)";
            } else {
                $taskManagement->completed_status = "On Time";
            }

            // send mail
            Mail::to($taskManagement->projects->profiles->email)
                ->cc(['codelogictechnologies@gmail.com', 'hr@cltech.com.np', 'info@cltech.com.np'])
                ->send(new TaskCompletedMail($taskManagement));

            $result = $taskManagement->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function storeMarks($post)
    {
        try {
            $freshData = sanitizeData($post);
            $taskManagement = TaskManagement::where('id', $freshData['id'])->first();
            $taskManagement->verified_by = $taskManagement->projects->project_lead_by;
            $currentDateTime = Carbon::now();
            $taskManagement->verified_date_ad = $currentDateTime->format('Y-m-d H:i:s');
            $taskManagement->achieved_point = $freshData['achieved_point'];
            $taskManagement->task_status = 'Verified';
            $taskManagement->response_from_supervisor = $freshData['response_from_supervisor'];
            $assignedMembers = DB::table('profiles')->whereIn('user_id', json_decode($taskManagement->assigned_to))->get();
            
            foreach ($assignedMembers as $members) {
                // $emails = $members->email;
                dispatch(new VerifiedMail($members, $taskManagement));
            }

            $result = $taskManagement->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function storeRemarks($post)
    {
        try {
            $freshData = sanitizeData($post);
            $taskManagement = TaskManagement::where('id', $freshData['id'])->first();
            $taskManagement->task_status = $freshData['value'];
            $taskManagement->feedback = $freshData['feedback'];
            $result = $taskManagement->save();

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

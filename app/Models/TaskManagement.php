<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ProjectManagement, Profile};
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCompletedMail;
use Illuminate\Support\Arr;
use App\Mail\SendAssignTaskMail;
use App\Mail\SendVerifiedMail;
use Carbon\Carbon;
use Str;
use DB;
use Auth;

class TaskManagement extends Model
{
    use HasFactory;

    protected $table = 'task_management';

    public function projects()
    {
        return $this->belongsTo(ProjectManagement::class, 'project_id', 'id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(Profile::class, 'assigned_by', 'user_id');
    }

    public static function fetchMembers()
    {
        try {
            $result = DB::table('profiles')
                        ->join('user_roles', 'profiles.user_id', '=', 'user_roles.user_id')
                        ->select('profiles.user_id', DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name) AS fullname"))
                        ->where(['user_roles.role_id'=>3, 'profiles.status'=>'Y', 'user_roles.status'=>'Y'])
                        ->orderBy('fullname', 'asc')
                        ->get();

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function storeData($post, $taskManagement, $request)
    {
        try {
            // dd($request->assigned_to);
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
            $taskManagement->task_description = $post['task_description'];
            $taskManagement->task_point = $freshData['task_point'];
            if ($freshData['id'] == null) {
                $taskManagement->assigned_by = Auth::user()->id;
                $taskManagement->task_status = 'Not Started Yet';
                $assignedTo = array_diff($request->assigned_to, [Auth::user()->id]);
                $assignedMembers = DB::table('profiles')->whereIn('user_id', $assignedTo)->get();
                foreach ($assignedMembers as  $member) {
                    Mail::to($member->email)->send(new SendAssignTaskMail($member, $taskManagement));
                }
            }
            $taskManagement->updated_by = Auth::user()->id;
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
            $cond = " TM.status = 'Y' ";

            if(!empty($userID)){
                $userJson = '"'.$userID.'"';

                /* the line of code for json format that supports in pgsql */
                $cond .= " AND project_lead_by = ".$userID." OR assigned_to::jsonb @> '[".$userJson."]' ";
                /* the line of code for json format that supports in pgsql */

                /* the line of code for json format that supports in mysql */
                // $cond .= " AND project_lead_by = ".$userID." OR JSON_CONTAINS(assigned_to, '[".$userJson."]', '$') ";
                /* the line of code for json format that supports in mysql */
            }

            $members = Self::fetchMembers();
            // dd($members);
            $memArray = [];
            foreach ($members as $row) {
                $memArray[strtolower($row->fullname)] = $row->user_id;
            }
            // dd($memArray);

            if ($get['sSearch_0']) {
                $cond .= "and lower(ticket_number) like'%".$get['sSearch_0']."%'";
            }

            if ($get['sSearch_1']) {
                $cond .= "and lower(task_title) like'%".$get['sSearch_1']."%'";
            }

            if ($get['sSearch_2']) {
                $cond .= "and TM.project_id = ".$get['sSearch_2'];
            }

            if ($get['sSearch_3']) {
                $taskType = strtolower(trim($get['sSearch_3']));
                $cond .= "and lower(task_type) = '".addslashes($taskType)."'";
            }

            if ($get['sSearch_6']) {
                $priority = strtolower(trim($get['sSearch_6']));
                $cond .= "and lower(priority) = '".addslashes($priority)."'";
            }

            if ($get['sSearch_7']) {
                $userIDSearch = '"'.$get['sSearch_7'].'"';
                $cond .= " AND assigned_to::jsonb @> '[$userIDSearch]' ";
            }

            if ($get['sSearch_8']) {
                $taskStatus = strtolower(trim($get['sSearch_8']));
                $cond .= "and lower(task_status) = '".addslashes($taskStatus)."'";
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
                        TM.assigned_to,
                        TM.assigned_by
                    FROM
                        task_management AS TM
                    JOIN
                        project_management AS PM ON TM.project_id = PM.id
                    WHERE $cond
                    ORDER BY TM.id
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
                $taskManagement->completed_status = "Early ($differenceInDays days)";
            } elseif ($differenceInDays < 0) {
                $differenceInDays = abs($differenceInDays);
                $taskManagement->completed_status = "Delay ($differenceInDays days)";
            } else {
                $taskManagement->completed_status = "On Time";
            }

            // send mail
            Mail::to($taskManagement->projects->profiles->email)
                ->cc('info@cltech.com.np')
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
            // dd((date('Y-m-d', strtotime($taskManagement->verified_date_ad))));
            $taskManagement->achieved_point = $freshData['achieved_point'];
            $taskManagement->task_status = 'Verified';
            $taskManagement->response_from_supervisor = $freshData['response_from_supervisor'];
            $assignedMembers = DB::table('profiles')->whereIn('user_id', json_decode($taskManagement->assigned_to))->get();
            
            foreach ($assignedMembers as $members) {
                Mail::to($members->email)->send(new SendVerifiedMail($members, $taskManagement));
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

    public static function getReport($post)
    {
        try {
            $sql = "SELECT *
                    FROM (
                    SELECT
                        P.user_id, 
                        CONCAT(first_name, ' ', middle_name, ' ', last_name) AS staffname
                    FROM
                        profiles AS P
                        JOIN user_roles AS UR ON UR.user_id = P.user_id 
                    WHERE
                        role_id = 3
                        AND P.status = 'Y' 
                        AND UR.status = 'Y'
                    ORDER BY
                        staffname ASC
                    ) AS S
                    LEFT JOIN (
                    SELECT
                        PM.project_name, TM.ticket_number, TM.task_title, TM.task_type, TM.task_start_date_bs,
                        TM.task_end_date_bs, TM.estimated_hour, TM.priority, assigned_to_array, TM.task_point,
                        TM.task_status, TM.completed_status, TM.achieved_point
                    FROM (
                        SELECT
                        project_id, ticket_number, task_title, task_type, task_start_date_bs,
                        task_end_date_bs, estimated_hour, priority, assigned_to, task_point,
                        task_status, completed_status, achieved_point,
                        json_array_elements_text(assigned_to::json) AS assigned_to_array
                        FROM task_management
                        WHERE status = 'Y' AND extract(month from task_start_date_bs) = ".$post['month']." AND extract(year from task_start_date_bs) = ".$post['year']."
                    ) AS TM
                    JOIN project_management AS PM ON PM.id = TM.project_id
                    WHERE PM.status = 'Y'
                    ) AS T ON S.user_id = T.assigned_to_array::int
                    ORDER BY staffname ASC";
                        
            $result = DB::select($sql);
            // dd($result);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

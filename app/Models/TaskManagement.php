<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Str;

class TaskManagement extends Model
{
    use HasFactory;

    protected $table = 'task_management';

    public static function storeData($post, $taskManagement)
    {
        try {
            $taskManagement->assigned_to = json_encode($post['assigned_to']);
            Arr::forget($post, 'assigned_to'); // to remove a given key value pair from an array
            $freshData = sanitizeData($post);
            $taskManagement->task_title = Str::title($freshData['task_title']);
            $taskManagement->project_id = $freshData['project_id'];
            $taskManagement->task_type = $freshData['task_type'];
            $taskManagement->task_start_date = $freshData['task_start_date'];
            $taskManagement->task_start_date_ad = $freshData['task_start_date_ad'];
            $taskManagement->task_end_date = $freshData['task_end_date'];
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

    public static function fetchTaskManagementInfo()
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " TM.status = 'Y'";

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
                        TM.task_title,
                        PM.project_name,
                        TM.task_type,
                        TM.task_start_date,
                        TM.task_end_date,
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
            $taskManagement->achieved_point = $freshData['achieved_point'];
            $taskManagement->task_status = 'Verified';
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

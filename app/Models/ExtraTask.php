<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str, Exception, Auth, NepaliDate;
use App\Models\ProjectManagement;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExtraTaskCompletedMail;
use App\Mail\ExtraTaskCreatedMail;
use App\Mail\ExtraTaskVerifiedMail;
use Carbon\Carbon;
use DB;

class ExtraTask extends Model
{
    use HasFactory;

    public function project()
    {
        return $this->belongsTo(ProjectManagement::class, 'project_id', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Profile::class, 'updated_by', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Profile::class, 'created_by', 'id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Profile::class, 'verified_by', 'id');
    }

    public static function storeData($post, $extraTask)
    {
        try {
            $freshData = sanitizeData($post);
            $extraTask->task_title = Str::title($freshData['task_title']);
            $extraTask->project_id = $freshData['project_id'];
            $extraTask->task_type = $freshData['task_type'];
            $extraTask->task_description = $post['task_description'];
            if (empty($post['id'])) {
                $extraTask->task_status = 'On Progress';
                $currentDateTime = Carbon::now();
                $timeComponent = $currentDateTime->toTimeString();
                $extraTask->task_created_date_ad = $currentDateTime->format('Y-m-d H:i:s');
                $extraTask->task_created_date_bs = NepaliDate::create($currentDateTime)->toBS() . ' ' . $timeComponent;

                if ($extraTask->created_by != $extraTask->project->project_lead_by) {
                    Mail::to($extraTask->project->profiles->email)->send(new ExtraTaskCreatedMail($extraTask));
                }
            }
            $result = $extraTask->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    public static function fetchExtraTaskInfo($userId)
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }

            $cond = " ET.status = 'Y' AND PM.status = 'Y'";

            if (!empty($userId)) {
                $cond .= " and created_by = $userId OR project_lead_by = $userId";
            }

            if ($get['sSearch_1']) {
                $cond .= " and lower(task_title) like'%".$get['sSearch_1']."%'";
            }

            if ($get['sSearch_2']) {
                $cond .= " and ET.project_id = ".$get['sSearch_2'];
            }

            if ($get['sSearch_3']) {
                $cond .= " and created_by = ".$get['sSearch_3'];
            }

            if ($get['sSearch_4']) {
                $taskType = strtolower(trim($get['sSearch_4']));
                $cond .= " and lower(task_type) = '".addslashes($taskType)."'";
            }

            if ($get['sSearch_5']) {
                $taskStatus = strtolower(trim($get['sSearch_5']));
                $cond .= " and lower(task_status) = '".addslashes($taskStatus)."'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }
            $sql = "SELECT
                        COUNT(*) OVER() AS totalrecs,
                        ET.id,
                        ticket_no,
                        task_title,
                        project_name,
                        task_type,
                        task_status,
                        created_by,
                        project_lead_by,
                        first_name,
                        profile
                    FROM
                        extra_tasks as ET
                    JOIN
                        project_management as PM
                    ON
                        ET.project_id = PM.id
                    JOIN
                        profiles as P
                    ON
                        ET.created_by = P.user_id
                    WHERE $cond
                    ORDER BY ET.id
                    DESC";
            // echo $sql; exit;
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

    public static function storeDocuments($post, $extraTask)
    {
        try {
            $currentDateTime = Carbon::now();
            $timeComponent = $currentDateTime->toTimeString();
            $extraTask->task_completed_date_ad = $currentDateTime->format('Y-m-d H:i:s');
            $extraTask->task_completed_date_bs = NepaliDate::create($currentDateTime)->toBS() . ' ' . $timeComponent;
            $extraTask->task_status = 'Completed';
            $extraTask->remarks = $post['remarks'];

            // send mail
            if ($extraTask->created_by != $extraTask->project->project_lead_by) {
                Mail::to($extraTask->project->profiles->email)
                    ->cc('info@cltech.com.np')
                    ->send(new ExtraTaskCompletedMail($extraTask));
            }

            $result = $extraTask->save();

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
            $extraTask = ExtraTask::where('id', $freshData['id'])->first();
            $currentDateTime = Carbon::now();
            $timeComponent = $currentDateTime->toTimeString();
            $extraTask->task_verified_date_ad = $currentDateTime->format('Y-m-d H:i:s');
            $extraTask->task_verified_date_bs = NepaliDate::create($currentDateTime)->toBS() . ' ' . $timeComponent;
            $extraTask->achieved_point = $freshData['achieved_point'];
            $extraTask->task_status = 'Verified';
            $extraTask->supervisor_response = $freshData['supervisor_response'];
            $extraTask->verified_by = Auth::user()->id;

            if ($extraTask->created_by != $extraTask->project->project_lead_by) {
                Mail::to($extraTask->createdBy->email)->send(new ExtraTaskVerifiedMail($extraTask));
            }

            $result = $extraTask->save();

            if($result){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getExtraReport($post)
    {
        try {
            $result = ExtraTask::whereRaw("extract(year from task_created_date_bs) = ? ", $post['year'])
                        ->whereRaw("extract(month from task_created_date_bs) = ? ", $post['month'])
                        ->where('status', 'Y')
                        ->get();

            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\{Common, TaskManagement};
use Illuminate\Database\QueryException;
use App\Exports\TaskReportExport;
use Excel;
use DB, Exception;

class TaskReportController extends Controller
{
    protected $type;
    protected $message;
    protected $response;
    protected $queryExceptionMessage;

    public function __construct ()
    {
        $this->type = 'success';
        $this->message = '';
        $this->response = false;
        $this->queryExceptionMessage = 'Something went wrong.';
    }

    public function index()
    {
        return view('backend.task-report.index');
    }

    public function fetch(Request $request)
    {
        $post = $request->only(['_token', 'year', 'month', 'monthName', 'isExport']);
        $this->message = "Data fetched successfully.";

        $fetchReport = TaskManagement::getReport($post);
        $staffArray = [];
        $staffResult = [];
        if (!empty($fetchReport)) {
            foreach ($fetchReport as $value) {
                $staffArray[$value->user_id][] = $value;
            }
            //====================================== for export to excel start ======================================
            if (!empty($request->isExport)) {
                $fileName = 'StaffTaskReport'.'-'.$post['monthName'].'-'.$post['year'].'.xlsx';
                return Excel::download(new TaskReportExport($staffArray), $fileName);
            }
            //====================================== for export to excel end ======================================

            foreach($staffArray as $key => $value){
                $data = [];
                $data['staffid'] = $key;
                $data['staffname'] = $value[0]->staffname;

                $assignedTasks = 0;
                $completedTasks = 0;
                $pendingTasks = 0;
                $notstartedTasks = 0;
                $achievedPoints = 0;
                foreach ($value as $values) {
                    if (!empty($values->ticket_number)) {
                        $assignedTasks++;
                        if($values->task_status == 'Completed'){
                            $completedTasks++;
                        }
                        if($values->task_status != 'Not Started Yet' || $values->task_status != 'Completed' || $values->task_status != 'Verified'){
                            $pendingTasks++;
                        }
                        if($values->task_status == 'Not Started Yet'){
                            $notstartedTasks++;
                        }
                        
                        $achievedPoints += !empty($values->achieved_point)?$values->achieved_point:0;
                    }
                }

                $data['assignedTasks'] = $assignedTasks;
                $data['completedTasks'] = $completedTasks;
                $data['pendingTasks'] = $pendingTasks;
                $data['notstartedTasks'] = $notstartedTasks;
                $data['achievedPoints'] = $achievedPoints;
                $data['extrapoints'] = '-';

                $staffResult[] = $data;
                
            }
        }
        
        $data = [
            'post' => $post,
            'report' => $staffResult,
        ];
        return view('backend.task-report.fetchdata',$data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\{Common, TaskManagement, UserRole};
use Illuminate\Database\QueryException;
use App\Exports\TaskReportExport;
use DB, Exception, Auth, Excel, NepaliDate;

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
        $nepaliDate = NepaliDate::create(Carbon::now())->toBS();
        $explodeDate = explode('-', $nepaliDate);
        $yearBS = $explodeDate[0];
        $monthBS = $explodeDate[1];
        $data = [
            'yearBS' => $yearBS,
            'monthBS' => $monthBS
        ];
        $userRole = UserRole::where('user_id', Auth::user()->id)->first();
        if($userRole->role_id == 3){
            return redirect()->route('admin.dashboard', session('username'))->with('error', 'You do not have permission to access this module');
        }
        return view('backend.task-report.index', $data);
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
                $cancelHoldTasks = 0;
                $notstartedTasks = 0;
                $achievedPoints = 0;
                foreach ($value as $values) {
                    if (!empty($values->ticket_number)) {
                        $assignedTasks++;

                        if($values->task_status == 'Not Started Yet'){
                            $notstartedTasks++;
                        }elseif($values->task_status == 'On Progress' || $values->task_status == 'Testing' || $values->task_status == 'Bug Fixing'){
                            $pendingTasks++;
                        }elseif($values->task_status == 'Cancelled' || $values->task_status == 'Hold'){
                            $cancelHoldTasks++;
                        }elseif($values->task_status == 'Completed' || $values->task_status == 'Verified'){
                            $completedTasks++;
                        }else{
                            // Nothing Here
                        }

                        $achievedPoints += !empty($values->achieved_point)?$values->achieved_point:0;
                    }
                }

                $data['assignedTasks'] = $assignedTasks;
                $data['completedTasks'] = $completedTasks;
                $data['pendingTasks'] = $pendingTasks;
                $data['cancelHoldTasks'] = $cancelHoldTasks;
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

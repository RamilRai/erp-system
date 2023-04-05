<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{TaskManagement, Common, Profile};
use App\Http\Requests\TaskManagementRequest;
use App\Http\Requests\TaskDocumentRequest;
use Illuminate\Database\QueryException;
use App\Traits\ImageProcessTrait;
use Carbon\Carbon;
use Exception;
use DB;
use Auth;

class TaskManagementController extends Controller
{
    use ImageProcessTrait;

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
        $data['members'] = TaskManagement::fetchMembers();
        // $data['projects'] = DB::table('project_management')->select('project_name')->where('status', 'Y')->get();
        $data['projects'] = DB::table('project_management')->select('id', 'project_name')->where('status', 'Y')->get();
        // $data['projects'] = [];
        // foreach ($projectsArray as  $item) {
        //     $data['projects'][] = $item->project_name;
        // }
        return view('backend.task-management.index', $data);
    }

    public function taskManagementCreate(Request $request)
    {
        $userRole = \App\Models\UserRole::where('user_id', Auth::user()->id)->first();
        $userRoleID = $userRole->role_id;
        $userID = '';

        $whereData = [
            'status' => 'Y'
        ];
        if($userRoleID == 3){
            $userID = Auth::user()->id;
            $whereData['project_lead_by'] = $userID;
        }
        $post = $request->only(['id', 'projectid', '_token']);
        if(!empty($post['projectid'])){
            $whereData['id'] = $post['projectid'];
        }
        $data = [];
        
        $data['projectName'] = DB::table('project_management')->select('id', 'project_name')->where($whereData)->orWhereRaw("assign_team_members::jsonb @> ?", '["' . $userID . '"]')->get();
        if (!empty($post['id'])) {
            $data['taskManagement'] = TaskManagement::where(['id'=>$post['id'], 'status'=>'Y'])->first();
            $data['assignedTeamMembers'] = json_decode($data['taskManagement']->assigned_to);
        }

        return view('backend.task-management.create', $data);
    }

    public function ckeditorFileUpload(Request $request)
    {
        if($request->has('upload')){
            $image = $request->file('upload');
            $extension=$image->getClientOriginalExtension();
            $filename=time().'.'.$extension;
            $image->move('storage/task-management',$filename);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('/storage/task-management/'.$filename);
            $msg = "Image uploaded.";
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum,'$url','$msg')</script>";
            @header('Content-type:text/html;charset=utf-8');
            echo $response;
        }
    }

    public function fetchTeamMembers(Request $request)
    {
        try {
            $post = $request->only(['_token', 'projectId', 'assignedMembersId']);
            $this->message = "Team Members fetched successfully.";

            $selectTeamMembers = DB::table('project_management')->select('assign_team_members')->where('id', $post['projectId'])->first();
            $teamMembersArray = json_decode($selectTeamMembers->assign_team_members);
            $teamMembers = DB::table('profiles')->select('id', 'user_id', 'first_name', 'middle_name', 'last_name')->whereIn('user_id', $teamMembersArray)->get();
            $this->response = [];
            foreach ($teamMembers as $value) {
                // to get selected value while editing start
                $assignedMembers = json_decode($post['assignedMembersId']);
                $selectMembers = '';
                if ($post['assignedMembersId'] != null) {
                    $selectMembers = in_array($value->user_id, $assignedMembers) ? "selected = 'selected'" : "";
                }
                // to get selected value while editing end
                $this->response[] = '<option value="'.$value->user_id.'" '. $selectMembers .'>'.$value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name .'</option>';
            }

        } catch (QueryException $qe) {
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch (Exception $e) {
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    public function taskManagementSubmit(TaskManagementRequest $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'task_title', 'project_id', 'task_type', 'task_start_date_bs', 'task_start_date_ad', 'task_end_date_bs', 'task_end_date_ad',
                    'estimated_hour', 'priority', 'assigned_to', 'task_point', 'task_description', 'projectName']);
            
            $this->message = $post['id'] == null ? "Task Management Information Submitted Successfully." : "Task Management Information Updated Successfully.";

            DB::beginTransaction();

            $taskManagement = $post['id'] == null ? new TaskManagement : TaskManagement::where('id', $post['id'])->first();

            // generate ticket number
            if ($post['id'] == null) {
                $countCurrentProjectTask = DB::table('task_management')->where('project_id', $post['project_id'])->count() + 1;
                $firstLetters = implode('', array_map(function($word) {
                    return strtoupper(substr($word, 0, 1));
                }, explode(' ', $post['projectName'])));
                $ticketNumber = $firstLetters . '-' . $countCurrentProjectTask;
                $taskManagement->ticket_number = $ticketNumber;
            }
            $sendDataToModel = TaskManagement::storeData($post, $taskManagement, $request);

            DB::commit();
        } catch (QueryException $qe) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch (Exception $e) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    public function taskManagementFetch()
    {
        $userRole = \App\Models\UserRole::where('user_id', Auth::user()->id)->first();
        $userRoleID = $userRole->role_id;
        $userID = '';

        if($userRoleID == 3){
            $userID = Auth::user()->id;
        }

        $data = TaskManagement::fetchTaskManagementInfo($userID);
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["ticketnumber"] = '#'.$row->ticket_number;
            $array[$i]["taskTitle"] = $row->task_title;
            $array[$i]["projectName"] = $row->project_name;
            $array[$i]["taskType"] = $row->task_type;
            $array[$i]["timeDuration"] = $row->task_start_date_bs . ' - ' . $row->task_end_date_bs;
            $array[$i]["estimatedHour"] = $row->estimated_hour;
            $array[$i]["priority"] = $row->priority;

            // assigned members
            $assignedTeamMembersArray = json_decode($row->assigned_to);
            $assignedTeamMembers = Profile::select('first_name', 'middle_name', 'last_name', 'profile')->whereIn('user_id', $assignedTeamMembersArray)->get();
            $assignTeams = '';
            foreach ($assignedTeamMembers as $key => $value) {
                $assignTeams .= '<img src="'.asset('storage/users-profile/'.$value->profile).'" title="'.$value->first_name.'" alt="image" style="height:2rem; width:2rem; border-radius:50%;">';
            }
            $array[$i]["assignedMembers"] = $assignTeams;

            $status = '';
            if ($row->task_status == 'Not Started Yet'){
                $status = '<span class="badge" style="background: #808080;">Not Started Yet</span>';
            } elseif ($row->task_status == 'On Progress'){
                $status = '<span class="badge" style="background: #0000FF;">On Progress</span>';
            } elseif ($row->task_status == 'Testing'){
                $status = '<span class="badge" style="background: #FFFF00; color: #000000">Testing</span>';
            } elseif ($row->task_status == 'Bug Fixing'){
                $status = '<span class="badge" style="background: #FFA500; color: #000000">Bug Fixing</span>';
            } elseif ($row->task_status == 'Completed'){
                $status = '<span class="badge" style="background: #00FF00; color: #000000">Completed</span>';
            } elseif ($row->task_status == 'Verified'){
                $status = '<span class="badge" style="background: #00FF00; color: #000000"">Verified</span>';
            } elseif ($row->task_status == 'Hold'){
                $status = '<span class="badge" style="background: #FFD700; color: #000000">Hold</span>';
            } elseif ($row->task_status == 'Cancelled'){
                $status = '<span class="badge" style="background: #FF0000;">Cancelled</span>';
            }
            $array[$i]["taskStatus"] = $status;

            if($row->project_lead_by == $userID || $userRoleID == 1 || $userRoleID == 2){
                $array[$i]["changeTaskStatus"] = '<select class="changeTaskStatus" data-id="'.$row->id.'">
                                                    <option value="Not Started Yet" ' .($row->task_status == "Not Started Yet" ? 'selected' : ''). '>Not Started Yet</option>
                                                    <option value="On Progress" '.($row->task_status == "On Progress" ? 'selected' : ''). '>On Progress</option>
                                                    <option value="Testing" '.($row->task_status == "Testing" ? 'selected' : ''). '>Testing</option>
                                                    <option value="Bug Fixing" '.($row->task_status == "Bug Fixing" ? 'selected' : ''). '>Bug Fixing</option>
                                                    <option value="Cancelled" '.($row->task_status == "Cancelled" ? 'selected' : ''). '>Cancelled</option>
                                                    <option value="Hold" '.($row->task_status == "Hold" ? 'selected' : ''). '>Hold</option>
                                                    <option value="Completed" '.($row->task_status == "Completed" ? 'selected' : ''). '>Completed</option>
                                                    <option value="Verified" '.($row->task_status == "Verified" ? 'selected' : ''). '>Verified</option>
                                                </select>';
            }else{
                $isHiddenStart = '';
                $isHiddenProgress = '';
                $isHiddenCompleted = '';
                $isHide = '';
                if($row->task_status == 'Not Started Yet'){
                    $isHiddenStart = '';
                    $isHiddenProgress = '';
                    $isHiddenCompleted = 'hidden';
                }elseif($row->task_status == 'Completed'){
                    $isHiddenStart = 'hidden';
                    $isHiddenProgress = 'hidden';
                    $isHiddenCompleted = '';
                    $isHide = 'Y';
                }elseif($row->task_status == 'Verified'){
                    $isHiddenStart = 'hidden';
                    $isHiddenProgress = 'hidden';
                    $isHiddenCompleted = 'hidden';
                    $isHide = 'Y';
                }else{
                    $isHiddenStart = 'hidden';
                    $isHiddenProgress = '';
                    $isHiddenCompleted = '';
                }

                if($isHide == 'Y'){
                    $array[$i]["changeTaskStatus"] = '';
                }else{

                    $array[$i]["changeTaskStatus"] = '<select class="changeTaskStatus" data-id="'.$row->id.'">
                                                        <option '.$isHiddenStart.' value="Not Started Yet" ' .($row->task_status == "Not Started Yet" ? 'selected' : ''). '>Not Started Yet</option>
                                                        <option '.$isHiddenProgress.' value="On Progress" '.($row->task_status == "On Progress" ? 'selected' : ''). '>On Progress</option>
                                                        <option '.$isHiddenProgress.' value="Testing" '.($row->task_status == "Testing" ? 'selected' : ''). '>Testing</option>
                                                        <option '.$isHiddenProgress.' value="Bug Fixing" '.($row->task_status == "Bug Fixing" ? 'selected' : ''). '>Bug Fixing</option>
                                                        <option '.$isHiddenProgress.' value="Cancelled" '.($row->task_status == "Cancelled" ? 'selected' : ''). '>Cancelled</option>
                                                        <option '.$isHiddenProgress.' value="Hold" '.($row->task_status == "Hold" ? 'selected' : ''). '>Hold</option>
                                                        <option '.$isHiddenCompleted.' value="Completed" '.($row->task_status == "Completed" ? 'selected' : ''). '>Completed</option>
                                                    </select>';
                }
            }



            // insert actions icons
            $action = '';
            if(($row->project_lead_by == $userID) || ($userRoleID == 1 || $userRoleID == 2) || $row->assigned_by == Auth::user()->id){
                // for edit
                $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editTaskManagement" style="color:blue; font-size: 20px" data-id="' . $row->id .  '" data-projectid="' . $row->projectid .  '"><i class="fa-solid fa-pen-to-square"></i></a>';
                // for delete
                $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteTaskManagement px-1" style="color:red; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-trash-can"></i></a>';
            }
            // for show
            $action .= '<a href="javascript:;" title="View Data" class="tooltipdiv viewTaskManagement" style="color:green; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-eye"></i></a>';
            $array[$i]["action"] = $action;
            $i++;
        }
        if (!$filtereddata) {
            $filtereddata = 0;
        }
        if (!$totalrecs) {
            $totalrecs = 0;
        }
        echo json_encode(array("recordsFiltered" => @$filtereddata, "recordsTotal" => @$totalrecs, "data" => $array));
        exit;
    }

    public function taskManagementView(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data['result'] = TaskManagement::where('id', $post['id'])->first();
        $assignedMembersIdArray = json_decode($data['result']->assigned_to);
        $data['assignedMembers'] = Profile::select('first_name', 'middle_name', 'last_name', 'profile')->whereIn('user_id', $assignedMembersIdArray)->get();

        return view('backend.task-management.view', $data);
    }

    public function taskManagementDelete(Request $request)
    {
        try {
            $post = $request->only(['id', '_token']);
            $this->message = 'Data deleted successfully.';

            $result = TaskManagement::where(['id'=>$post['id']])->update(['status'=>'N']);

            if (!$result){
                throw new Exception ("Couldn't process request.", 1);
            }

        } catch (QueryException $e) {
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch(Exception $e){
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    public function changeTaskStatus(Request $request)
    {
        try{
            $post = $request->only(['value', 'id', '_token']);
            $this->message = 'Task Status Changed Successfully.';

            DB::beginTransaction();

            $getCurrentTask = TaskManagement::where(['id'=>$post['id']])->first();

            //store started time if on progress is selected
            if ($post['value'] == 'On Progress' && $getCurrentTask->task_started_date_and_time_ad == null) {
                $currentDateTime = Carbon::now();
                $getCurrentTask->task_started_date_and_time_ad = $currentDateTime->format('Y-m-d H:i:s');
            }

            //open model for file upload if completed is selected
            if ($post['value'] == 'Completed') {
                $this->response = 'completed';
            }

            //open model for marks entry form if verified is selected
            if ($post['value'] == 'Verified') {
                $this->response = 'verified';
            }

            //open model for remarks form if cancelled or hold is selected
            if ($post['value'] == 'Cancelled' || $post['value'] == 'Hold' ) {
                $this->response = 'revoke';
            }

            //update task status
            if ($post['value'] == 'On Progress' || $post['value'] == 'Testing' || $post['value'] == 'Bug Fixing') {
                $getCurrentTask->task_status = $post['value'];

                $result = $getCurrentTask->save();

                if (!$result){
                    throw new Exception ("Couldn't process request.", 1);
                }
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch(Exception $e){
            DB::rollback();
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    //=========================================== task status to completed start ===========================================

    public function taskManagementDocuments(Request $request)
    {
        $data['taskId'] = $request->id;
        return view('backend.task-management.documents', $data);
    }

    public function taskManagementDocumentsSubmit(Request $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'documents', 'feedback']);

            $this->message = "Task Status Changed And Documents For Current Task Submitted Successfully.";

            DB::beginTransaction();

            $taskManagement = TaskManagement::where('id', $post['id'])->first();

            $arrayDocuments = [];
            foreach ($request->file('documents') as $image) {
                $folder = 'storage/task-documents';
                $fileName = $image->hashName();
                $fileExtension = $image->getClientOriginalExtension();
                if ($fileExtension == 'pdf' || $fileExtension == 'docx') {
                    $image->move(public_path($folder), $fileName);
                } else {
                    $this->uploadImage($image, $folder, $fileName);
                }

                $arrayDocuments[] = $fileName;
            }
            $taskManagement->documents = json_encode($arrayDocuments);

            $sendDataToModel = TaskManagement::storeDocuments($post, $taskManagement);

            DB::commit();
        } catch (QueryException $qe) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch (Exception $e) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    //=========================================== task status to completed end ===========================================

    //=========================================== task status to verified start ===========================================

    public function taskManagementMarks(Request $request)
    {
        $data['currentTask'] = TaskManagement::select('id', 'feedback', 'task_point')->where('id', $request->id)->first();
        return view('backend.task-management.marks', $data);
    }

    public function taskManagementMarksSubmit(Request $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'achieved_point', 'response_from_supervisor']);

            $this->message = "Task Status Changed And Marks For Current Task Submitted Successfully.";

            DB::beginTransaction();

            $sendDataToModel = TaskManagement::storeMarks($post);

            DB::commit();
        } catch (QueryException $qe) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch (Exception $e) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    //=========================================== task status to verified end ===========================================

    //=========================================== task status to cancelled or hold start ===========================================

    public function taskManagementRevoke(Request $request)
    {
        $data['taskId'] = $request->id;
        $data['taskValue'] = $request->value;
        return view('backend.task-management.revoke', $data);
    }

    public function taskManagementRevokeSubmit(Request $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'value', 'feedback']);

            $this->message = "Task Status Changed And Remarks For Current Task Submitted Successfully.";

            DB::beginTransaction();

            $sendDataToModel = TaskManagement::storeRemarks($post);

            DB::commit();
        } catch (QueryException $qe) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch (Exception $e) {
            DB::rollback();
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    //=========================================== task status to cancelled or hold end ===========================================
}

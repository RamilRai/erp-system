<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ExtraTask, Common, UserRole, TaskManagement};
use App\Http\Requests\ExtraTaskRequest;
use Illuminate\Database\QueryException;
use App\Traits\ImageProcessTrait;
use DB, Exception, Auth;

class ExtraTaskController extends Controller
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
        $userId = Auth::user()->id;
        $userRole = UserRole::where('user_id', $userId)->first();
        $userRoleID = $userRole->role_id;
        if ($userRoleID == 3) {
            $data['projects'] = DB::table('project_management')
                                ->select('id', 'project_name')
                                ->whereRaw("CAST(assign_team_members AS text) LIKE '%$userId%'")
                                ->where('status', 'Y')
                                ->get();
        } else {
            $data['projects'] = DB::table('project_management')->select('id', 'project_name')->where('status', 'Y')->get();
        }

        $data['members'] = TaskManagement::fetchMembers();

        return view('backend.extra-task.index', $data);
    }

    public function extraTaskCreate(Request $request)
    {
        $post = $request->all();
        $data = [];
        $userId = Auth::user()->id;
        $data['projectName'] = DB::table('project_management')->select('id', 'project_name')
                                ->whereRaw("CAST(assign_team_members AS text) LIKE '%$userId%'")
                                ->where('status', 'Y')
                                ->get();
        if (!empty($post['id'])) {
            $data['extraTask'] = ExtraTask::where(['id'=>$post['id'], 'status'=>'Y'])->first();
        }

        return view('backend.extra-task.create', $data);
    }

    public function ckeditorFileUpload(Request $request)
    {
        if($request->has('upload')){
            $image = $request->file('upload');
            $extension=$image->getClientOriginalExtension();
            $filename=time().'.'.$extension;
            $image->move('storage/extra-task',$filename);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('/storage/extra-task/'.$filename);
            $msg = "Image uploaded.";
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum,'$url','$msg')</script>";
            @header('Content-type:text/html;charset=utf-8');
            echo $response;
        }
    }

    public function extraTaskSubmit(ExtraTaskRequest $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'task_title', 'project_id', 'task_type', 'task_description']);

            $this->message = $post['id'] == null ? "Extra Task Information Submitted Successfully." : "Extra Task Information Updated Successfully.";

            DB::beginTransaction();

            $extraTask = $post['id'] == null ? new ExtraTask : ExtraTask::where('id', $post['id'])->first();
            $userId = Auth::user()->id;

            // generate ticket number
            if ($post['id'] == null) {
                $countCurrentProjectTask = DB::table('extra_tasks')->count() + 1;
                $ticketNumber = 'EXT-' . $countCurrentProjectTask;
                $extraTask->ticket_no = $ticketNumber;
                $extraTask->created_by = $userId;
            }
            if (!empty($post['id'])) {
                $extraTask->updated_by = $userId;
            }
            ExtraTask::storeData($post, $extraTask);

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

    public function extraTaskFetch()
    {
        $userRole = UserRole::where('user_id', Auth::user()->id)->first();
        $userRoleID = $userRole->role_id;
        $userId = '';

        if ($userRoleID == 3) {
            $userId = Auth::user()->id;
        }

        $data = ExtraTask::fetchExtraTaskInfo($userId);
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["ticketNo"] = '#'.$row->ticket_no;
            $array[$i]["taskTitle"] = $row->task_title;
            $array[$i]["projectName"] = $row->project_name;
            $img = '<img src="'.asset('storage/users-profile/'.$row->profile).'" alt="image" style="height:2rem; width:2rem; border-radius:50%;">';
            $array[$i]["taskCreatedBy"] = $img . ' ' .$row->first_name;
            $array[$i]["taskType"] = $row->task_type;
            $status = '';
            if ($row->task_status == 'On Progress'){
                $status = '<span class="badge" style="background: #0000FF;">On Progress</span>';
            } elseif ($row->task_status == 'Completed'){
                $status = '<span class="badge" style="background: #00FF00; color: #000000">Completed</span>';
            } elseif ($row->task_status == 'Verified'){
                $status = '<span class="badge" style="background: #00FF00; color: #000000"">Verified</span>';
            }
            $array[$i]["taskStatus"] = $status;
            if ($userRoleID == 1 || $userRoleID == 2) {
                $array[$i]["changeTaskStatus"] = '<select class="changeTaskStatus" data-id="'.$row->id.'">
                                                    <option hidden value="On Progress" '.($row->task_status == "On Progress" ? 'selected' : ''). '>On Progress</option>
                                                    <option value="Completed" '.($row->task_status == "Completed" ? 'selected' : ''). '>Completed</option>
                                                    <option value="Verified" '.($row->task_status == "Verified" ? 'selected' : ''). '>Verified</option>
                                                </select>';
            } else {
                $isHide = '';
                $isCompletedHidden = '';
                $isVerifiedHidden = '';
                if ($row->task_status == "On Progress") {
                    if ($row->project_lead_by != $userId) {
                        $isVerifiedHidden = 'hidden';
                    }
                } elseif($row->task_status == "Completed") {
                    $isHide = 'Y';
                    if ($row->project_lead_by == $userId) {
                        $isHide = '';
                        $isCompletedHidden = 'hidden';
                        $isVerifiedHidden = '';
                    }
                } elseif($row->task_status == "Verified") {
                    $isHide = 'Y';
                }

                if ($isHide == 'Y') {
                    $array[$i]["changeTaskStatus"] = '';
                } else {
                    $array[$i]["changeTaskStatus"] = '<select class="changeTaskStatus" data-id="'.$row->id.'">
                                                        <option hidden value="On Progress" '.($row->task_status == "On Progress" ? 'selected' : ''). '>On Progress</option>
                                                        <option '.$isCompletedHidden.' value="Completed" '.($row->task_status == "Completed" ? 'selected' : ''). '>Completed</option>
                                                        <option '.$isVerifiedHidden.' value="Verified" '.($row->task_status == "Verified" ? 'selected' : ''). '>Verified</option>
                                                    </select>';
                }
            }

            // insert actions icons
            $action = '';
            // for edit
            $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editExtraTask" style="color:blue; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-pen-to-square"></i></a>';
            // for delete
            $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteExtraTask px-1" style="color:red; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-trash-can"></i></a>';
            // for show
            $action .= '<a href="javascript:;" title="View Data" class="tooltipdiv viewExtraTask" style="color:green; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-eye"></i></a>';
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

    public function extraTaskView(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data['result'] = ExtraTask::where('id', $post['id'])->first();

        return view('backend.extra-task.view', $data);
    }

    public function extraTaskDelete(Request $request)
    {
        try {
            $post = $request->only(['id', '_token']);
            $this->message = 'Data deleted successfully.';

            $result = ExtraTask::where(['id'=>$post['id']])->update(['status'=>'N']);

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

            DB::beginTransaction();

            //open model for file upload if completed is selected
            if ($post['value'] == 'Completed') {
                $this->response = 'completed';
            }

            //open model for marks entry form if verified is selected
            if ($post['value'] == 'Verified') {
                $this->response = 'verified';
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

    public function extraTaskDocuments(Request $request)
    {
        $data['taskId'] = $request->id;
        return view('backend.extra-task.documents', $data);
    }

    public function extraTaskDocumentsSubmit(Request $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'documents', 'remarks']);

            $this->message = "Task Status Changed And Documents For Current Task Submitted Successfully.";

            DB::beginTransaction();

            $extraTask = ExtraTask::where('id', $post['id'])->first();

            $arrayDocuments = [];
            foreach ($request->file('documents') as $image) {
                $folder = 'storage/extra-task-documents';
                $fileName = $image->hashName();
                $fileExtension = $image->getClientOriginalExtension();
                if ($fileExtension == 'pdf' || $fileExtension == 'docx') {
                    $image->move(public_path($folder), $fileName);
                } else {
                    $this->uploadImage($image, $folder, $fileName);
                }

                $arrayDocuments[] = $fileName;
            }
            $extraTask->documents = json_encode($arrayDocuments);

            ExtraTask::storeDocuments($post, $extraTask);

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

    public function extraTaskMarks(Request $request)
    {
        $data['currentTask'] = ExtraTask::select('id', 'remarks', 'task_created_date_ad', 'task_completed_date_ad')->where('id', $request->id)->first();
        return view('backend.extra-task.marks', $data);
    }

    public function extraTaskMarksSubmit(Request $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'achieved_point', 'supervisor_response']);

            $this->message = "Task Status Changed And Marks For Current Task Submitted Successfully.";

            DB::beginTransaction();

            ExtraTask::storeMarks($post);

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

}

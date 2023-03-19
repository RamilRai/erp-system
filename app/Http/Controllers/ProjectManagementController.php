<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ProjectManagement, Common, Profile};
use App\Http\Requests\ProjectManagementRequest;
use Illuminate\Database\QueryException;
use Exception;
use Storage;
use DB;
use Auth;

class ProjectManagementController extends Controller
{
    protected $type;
    protected $message;
    protected $response;
    protected $queryExceptionMessage;

    public function __construct ()
    {
        $this->type = 'success';
        $this->message = '';
        $this->reponse = false;
        $this->queryExceptionMessage = 'Something went wrong.';
    }

    public function index()
    {
        $userRole = \App\Models\UserRole::where('user_id', Auth::user()->id)->first();
        $data['userRoleID'] = $userRole->role_id;
        return view('backend.project-management.index',$data);
    }

    public function projectManagementCreate(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data = [];
        $data['projectLeader'] = DB::table('profiles')->where('status', 'Y')->get();
        $data['teamMembers'] = DB::table('profiles as P')->join('user_roles as UR', 'P.user_id', '=', 'UR.user_id')->where(['role_id'=>3, 'P.status'=>'Y', 'UR.status'=>'Y'])->get();
        $data['customers'] = DB::table('customers')->where('status', 'Y')->get();
        if (!empty($post['id'])) {
            $data['projectManagement'] = ProjectManagement::where(['id'=>$post['id'], 'status'=>'Y'])->first();
            $data['assignedTeamMembers'] = json_decode($data['projectManagement']->assign_team_members);
        }

        return view('backend.project-management.create', $data);
    }

    public function projectManagementSubmit(ProjectManagementRequest $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'project_name', 'pdf', 'project_type', 'customer_id', 'project_time_duration',
                    'start_date_bs', 'start_date_ad', 'end_date_bs', 'end_date_ad', 'project_lead_by', 'assign_team_members', 'project_status']);

            $this->message = $post['id'] == null ? "Project Management Information Submitted Successfully." : "Project Management Information Updated Successfully.";

            DB::beginTransaction();

            $projectManagement = $post['id'] == null ? new ProjectManagement : ProjectManagement::where('id', $post['id'])->first();
            if ($request->has('pdf') && $post['pdf'] != null) {
                Storage::delete('public/projects-pdf/'.$projectManagement->pdf);
                $fileName = $post['pdf']->hashName();
                $post['pdf']->move(public_path('storage/projects-pdf/'), $fileName);
                $projectManagement->pdf = $fileName;
            }

            $sendDataToModel = ProjectManagement::storeData($post, $projectManagement);

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

    public function projectManagementFetch()
    {
        $userRole = \App\Models\UserRole::where('user_id', Auth::user()->id)->first();
        $userRoleID = $userRole->role_id;
        $userID = '';
        if($userRoleID == 3){
            $userID = Auth::user()->id;
        }

        $data = ProjectManagement::fetchProjectManagementInfo($userID);
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["sn"] = $i +1;
            $array[$i]["projectName"] = $row->project_name;
            $array[$i]["projectType"] = $row->project_type;
            $array[$i]["timeDuration"] = $row->start_date_bs . ' - ' . $row->end_date_bs;

            // project leader
            $img = '<img src="'.asset('storage/users-profile/'.$row->profile).'" alt="image" style="height:2rem; width:2rem; border-radius:50%;">';
            $array[$i]["leadBy"] = $img . ' ' .$row->first_name;

            // assigned members
            $assignedTeamMembersArray = json_decode($row->assign_team_members);
            $assignedTeamMembers = Profile::select('first_name', 'middle_name', 'last_name', 'profile')->whereIn('user_id', $assignedTeamMembersArray)->get();
            $assignTeams = '';
            foreach ($assignedTeamMembers as $key => $value) {
                $assignTeams .= '<img src="'.asset('storage/users-profile/'.$value->profile).'" title="'.$value->first_name.'" alt="image" style="height:2rem; width:2rem; border-radius:50%;">';
            }
            $array[$i]["assignedMembers"] = $assignTeams;

            $progressBar = '<div class="progress" style="background-color:#D6D6D6;border-radius: 10px;"><div class="progress-bar" role="progressbar" title="10%" style="width: 15%; background-color: #4B8F4B" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>';
            $array[$i]["workProgress"] = $progressBar;
            $status = '';
            if ($row->project_status == 'Not Started Yet'){
                $status = '<span class="badge" style="background: #808080;">Not Started Yet</span>';
            } elseif ($row->project_status == 'On Progress'){
                $status = '<span class="badge" style="background: #0000FF;">On Progress</span>';
            } elseif ($row->project_status == 'Testing'){
                $status = '<span class="badge" style="background: #FFFF00; color: #000000">Testing</span>';
            } elseif ($row->project_status == 'Bug Fixing'){
                $status = '<span class="badge" style="background: #FFA500; color: #000000">Bug Fixing</span>';
            } elseif ($row->project_status == 'Completed'){
                $status = '<span class="badge" style="background: #00FF00; color: #000000">Completed</span>';
            } elseif ($row->project_status == 'Dropped'){
                $status = '<span class="badge" style="background: #A020F0;">Dropped</span>';
            } elseif ($row->project_status == 'Hold'){
                $status = '<span class="badge" style="background: #FFD700; color: #000000">Hold</span>';
            } elseif ($row->project_status == 'Cancelled'){
                $status = '<span class="badge" style="background: #FF0000;">Cancelled</span>';
            }
            $array[$i]["projectStatus"] = $status;
            $array[$i]["changeProjectStatus"] = '<select class="changeProjectStatus" data-id="'.$row->id.'" >
                                                    <option value="Not Started Yet" ' .($row->project_status == "Not Started Yet" ? 'selected' : ''). '>Not Started Yet</option>
                                                    <option value="On Progress" '.($row->project_status == "On Progress" ? 'selected' : ''). '>On Progress</option>
                                                    <option value="Testing" '.($row->project_status == "Testing" ? 'selected' : ''). '>Testing</option>
                                                    <option value="Bug Fixing" '.($row->project_status == "Bug Fixing" ? 'selected' : ''). '>Bug Fixing</option>
                                                    <option value="Completed" '.($row->project_status == "Completed" ? 'selected' : ''). '>Completed</option>
                                                    <option value="Dropped" '.($row->project_status == "Dropped" ? 'selected' : ''). '>Dropped</option>
                                                    <option value="Hold" '.($row->project_status == "Hold" ? 'selected' : ''). '>Hold</option>
                                                    <option value="Cancelled" '.($row->project_status == "Cancelled" ? 'selected' : ''). '>Cancelled</option>
                                                </select>';

            // insert actions icons
            $action = '';
            if($userRoleID == 1 || $userRoleID == 2){
                // for edit
                $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editProjectManagement" style="color:blue; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-pen-to-square"></i></a>';

                // for delete
                $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteProjectManagement px-1" style="color:red; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-trash-can"></i></a>';
            }
            // for show
            $action .= '<a href="javascript:;" title="View Data" class="tooltipdiv viewProjectManagement" style="color:green; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-eye"></i></a>';
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

    public function projectManagementView(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data['result'] = ProjectManagement::where('id', $post['id'])->first();
        $assignedMembersIdArray = json_decode($data['result']->assign_team_members);
        $data['assignedMembers'] = Profile::select('first_name', 'middle_name', 'last_name', 'profile')->whereIn('user_id', $assignedMembersIdArray)->get();

        return view('backend.project-management.view', $data);
    }

    public function projectManagementDelete(Request $request)
    {
        try {
            $post = $request->only(['id', '_token']);
            $this->message = 'Data deleted successfully.';

            $result = ProjectManagement::where(['id'=>$post['id']])->update(['status'=>'N']);

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

    public function changeProjectStatus(Request $request)
    {
        try{
            $post = $request->only(['value', 'id', '_token']);
            $this->message = 'Project Status Changed Successfully.';

            $result = ProjectManagement::where(['id'=>$post['id']])->update(['project_status'=>$post['value']]);
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
}

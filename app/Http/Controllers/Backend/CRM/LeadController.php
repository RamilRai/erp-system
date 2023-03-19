<?php

namespace App\Http\Controllers\Backend\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CRM\{Lead, CallLog};
use App\Models\Common;
use App\Http\Requests\LeadRequest;
use App\Http\Requests\CallLogRequest;
use Illuminate\Database\QueryException;
use DB;
use Exception;

class LeadController extends Controller
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

    //====================================== lead start ======================================

    public function index()
    {
        return view('backend.crm.leads.index');
    }

    public function leadCreate(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data = [];
        if (!empty($post['id'])) {
            $data['lead'] = Lead::where(['id'=>$post['id'], 'active_status'=>'Y'])->first();
        }

        return view('backend.crm.leads.create', $data);
    }

    public function leadSubmit(LeadRequest $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'organization_type', 'organization_name', 'address', 'email',
                            'contact_number', 'mobile_number', 'lead_by_name', 'lead_date', 'lead_status']);
            $this->message = $post['id'] == null ? "Lead Information Submitted Successfully." : "Lead Information Updated Successfully.";

            DB::beginTransaction();

            $storeLead = Lead::storeLead($post);

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

    public function leadFetch()
    {
        $data = Lead::fetchLeadInfo();
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["sn"] = $i +1;
            $array[$i]["organizationType"] = $row->organization_type;
            $array[$i]["organizationName"] = $row->organization_name;
            $array[$i]["address"] = $row->address;
            $array[$i]["leadByName"] = $row->lead_by_name;
            $status = '';
            if ($row->lead_status == 'Pending'){
                $status = '<span class="badge badge-warning" style="background: #ffcc00;">Pending</span>';
            } elseif ($row->lead_status == 'Active'){
                $status = '<span class="badge badge-success" style="background: green;">Active</span>';
            } elseif ($row->lead_status == 'Cancelled'){
                $status = '<span class="badge badge-danger" style="background: #cc3300;">Cancelled</span>';
            }
            $array[$i]["leadStatus"] = '<p>'.$status.'</p>';

            $array[$i]["changeLeadStatus"]  = '<select class="changeLeadStatus" data-id="' . $row->id .  '" >
                                                <option value="Pending" ' .($row->lead_status == "Pending" ? 'selected' : ''). '>Pending</option>
                                                <option value="Active" '.($row->lead_status == "Active" ? 'selected' : ''). '>Active</option>
                                                <option value="Cancelled" '.($row->lead_status == "Cancelled" ? 'selected' : ''). '>Cancelled</option>
                                            </select>';
            // insert actions icons
            $action = '';
            // for edit
            $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editLead" style="color:blue; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-pen-to-square"></i></a>';
            // for delete
            $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteLead px-2" style="color:red; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-trash-can"></i></a>';
            // for show
            $action .= '<a href="javascript:;" title="View Data" class="tooltipdiv viewLead" style="color:green; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-eye"></i></a>';

            $action .= '<a href="javascript:;" title="Call Log" class="tooltipdiv callLog ps-2" style="color:green; font-size: 18px" data-id="' . $row->id .  '"><i class="fa-solid fa-phone"></i></a>';
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

    public function leadView(Request $request)
    {
        $post = $request->all();
        $data['result'] = Lead::viewLeadData($post);

        return view('backend.crm.leads.view', $data);
    }

    public function leadDelete(Request $request)
    {
        try{
            $post = $request->only(['id', '_token']);
            $this->message = 'Data deleted successfully.';

            $result = Lead::deleteLeadData($post);
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

    public function changeLeadStatus(Request $request)
    {
        try{
            $post = $request->only(['value', 'id', '_token']);
            $this->message = 'Lead Status Changed Successfully.';

            $result = Lead::changeLeadStatus($post);
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

    //====================================== lead end ======================================

    //====================================== call log start ======================================

    public function leadCallLog(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data = [
            'post' => $post
        ];
        return view('backend.crm.leads.call-log',$data);
    }

    public function callLogSubmit(CallLogRequest $request)
    {
        try {
            $post = $request->only(['_token', 'lead_id', 'id', 'call_date', 'called_by', 'received_by', 'remarks']);
            $this->message = $post['id'] == null ? "Information Submitted Successfully." : "Information Updated Successfully.";

            DB::beginTransaction();

            $storeCallLog = CallLog::storeCallLog($post);

            DB::commit();
        } catch (QueryException $e) {
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

    public function callLogFetch()
    {
        $data = CallLog::fetchCallLogInfo();
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["sn"] = $i +1;
            $array[$i]["calledDate"] = $row->call_date;
            $array[$i]["calledBy"] = $row->called_by;
            $array[$i]["receivedBy"] = $row->received_by;
            // insert actions icons
            $action = '';
            // for edit
            $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editCallLog" style="color:blue; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-pen-to-square"></i></a>';
            // for delete
            $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteCallLog px-2" style="color:red; font-size: 20px" data-id="' . $row->id .  '"><i class="fa-solid fa-trash-can"></i></a>';

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

    public function callLogEdit(Request $request)
    {
        try {
            $post = $request->only(['id', '_token']);
            $this->message = "Call Log data fetched successfully";

            $this->response = CallLog::where('id', $post['id'])->first();

            if (!$this->response) {
                throw new Exception("Error Processing Request", 1);
            }
        } catch (QueryException $e) {
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch (Exception $e) {
            $this->type = "error";
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    public function callLogDelete(Request $request)
    {
        try{
            $post = $request->only(['id', '_token']);
            $this->message = 'Data deleted successfully.';

            $result = CallLog::where('id', $post['id'])->update(['status'=>'N']);

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

    //====================================== call log end ======================================

}

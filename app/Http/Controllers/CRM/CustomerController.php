<?php

namespace App\Http\Controllers\CRM;

use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CRM\{Customer, Service, CustomerCallLog};
use App\Models\Common;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CRM\ServiceRequest;
use App\Http\Requests\CRM\CustomerCallLogRequest;
use Exception;
use DB;
use Carbon\Carbon;

class CustomerController extends Controller
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
        return view('backend.crm.customers.index');
    }

    public function customerCreate(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data = [];
        $data['serviceType'] = Service::select('id','service_type')->where('status', 'Y')->get();
        if (!empty($post['id'])) {
            $data['customer'] = Customer::where(['id'=>$post['id'], 'status'=>'Y'])->first();
        }

        return view('backend.crm.customers.create', $data);
    }

    public function customerSubmit(CustomerRequest $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'company_name', 'owner_name', 'email', 'address','mobile_number', 'landline_number', 'service_id',
                            'service_name', 'domain_name', 'company_website', 'contracted_date', 'contract_end_date', 'contract_end_date_ad', 'contracted_by', 'remarks']);
            $this->message = $post['id'] == null ? "Customer Information Submitted Successfully." : "Customer Information Updated Successfully.";

            DB::beginTransaction();

            $storeCustomer = Customer::storeCustomer($post);

            if (!$storeCustomer) {
                throw new Exception("Error Processing Request", 1);
            }

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

    public function customerFetch()
    {
        $data = Customer::fetchCustomerInfo();
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["sn"] = $i +1;
            $array[$i]["companyName"] = $row->company_name;
            $array[$i]["ownerName"] = $row->owner_name;
            $array[$i]["email"] = $row->email;
            $array[$i]["mobileNumber"] = $row->mobile_number;
            $array[$i]["serviceName"] = $row->service_name;

            // for contract status
            $currentDate = Carbon::parse(date('Y-m-d'));
            $endDate = Carbon::parse($row->contract_end_date_ad);
            $diff = $currentDate->diff($endDate);
            $dateDiff = $diff->days * ($diff->invert ? -1 : 1);
            // dd($dateDiff);
            if ($dateDiff > 1) {
                $array[$i]["contractStatus"] = $dateDiff . ' days left';
            } elseif($dateDiff == 1) {
                $array[$i]["contractStatus"] = $dateDiff . ' day left';
            } elseif($dateDiff == 0) {
                $array[$i]["contractStatus"] = 'Expired Today';
            } elseif ($dateDiff == -1) {
                $array[$i]["contractStatus"] = 'Expired 1 day ago';
            } elseif ($dateDiff < -1) {
                $expiredDays = str_replace('-', '', $dateDiff);
                $array[$i]["contractStatus"] = 'Expired ' . $expiredDays . ' days ago';
            }
            $array[$i]['lastCallDate'] = $row->call_date . '<sup>'.$row->totalcalls.'</sup>';
            
            // insert actions icons
            $action = '';
            // for edit
            $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editCustomer" style="color:blue; font-size: 20px" data-id="' . $row->cust_id .  '"><i class="fa-solid fa-pen-to-square"></i></a>';
            // for delete
            $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteCustomer px-2" style="color:red; font-size: 20px" data-id="' . $row->cust_id .  '"><i class="fa-solid fa-trash-can"></i></a>';
            // for show
            $action .= '<a href="javascript:;" title="View Data" class="tooltipdiv viewCustomer" style="color:green; font-size: 20px" data-id="' . $row->cust_id .  '"><i class="fa-solid fa-eye"></i></a>';

            $action .= '<a href="javascript:;" title="Call Log" class="tooltipdiv callLog ps-2" style="color:green; font-size: 18px" data-id="' . $row->cust_id .  '"><i class="fa-solid fa-phone"></i></a>';
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

    public function customerView(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data['result'] = Customer::where('id', $post['id'])->first();

        return view('backend.crm.customers.view', $data);
    }

    public function customerDelete(Request $request)
    {
        try {
            $post = $request->only(['id', '_token']);
            $this->message = 'Data deleted successfully.';

            $result = Customer::where(['id'=>$post['id']])->update(['status'=>'N']);

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

    //====================================== service type start ======================================

    public function serviceTypeLoad()
    {
        try {
            $this->message = "Service Type Fetched Successfully.";

            $this->response = Service::select('id', 'service_type')->where('status', 'Y')->get();

            if (!$this->response) {
                throw new Exception("Error Processing Request", 1);
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

    public function serviceTypeForm()
    {
        return view('backend.crm.customers.service-type');
    }

    public function serviceTypeSubmit(Request $request)
    {
        try {
            $post = $request->only(['_token', 'service_type']);
            $this->message = "Service Type Submitted Successfully.";

            DB::beginTransaction();

            $storeService = Service::storeService($post);

            if (!$storeService) {
                throw new Exception("Error Processing Request", 1);
            }

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

    //====================================== service type end ======================================

    //====================================== call log start ======================================
    public function customerCallLog(Request $request)
    {
        $post = $request->only(['id', '_token']);
        $data = [
            'post' => $post
        ];
        return view('backend.crm.customers.call-log',$data);
    }

    public function callLogSubmit(CustomerCallLogRequest $request)
    {
        try {
            $post = $request->only(['_token', 'customer_id', 'id', 'call_date', 'called_by', 'received_by', 'remarks']);
            
            $this->message = $post['id'] == null ? "Information Submitted Successfully." : "Information Updated Successfully.";

            DB::beginTransaction();

            $storeCallLog = CustomerCallLog::storeCallLog($post);

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
        $data = CustomerCallLog::fetchCallLogInfo();
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

            $this->response = CustomerCallLog::where('id', $post['id'])->first();
            
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

            $result = CustomerCallLog::where('id', $post['id'])->update(['status'=>'N']);

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

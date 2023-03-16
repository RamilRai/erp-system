<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRM\Service;
use Str;
use Exception;
use DB;

class Customer extends Model
{
    use HasFactory;

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public static function storeCustomer($post)
    {
        try {
            $freshData = sanitizeData($post);
            $customer = $freshData['id'] == null ? new Customer : Customer::where('id', $freshData['id'])->first();
            $customer->company_name = Str::title($freshData['company_name']);
            $customer->owner_name = Str::title($freshData['owner_name']);
            $customer->email = $freshData['email'];
            $customer->address = Str::title($freshData['address']);
            $customer->mobile_number = $freshData['mobile_number'];
            $customer->landline_number = $freshData['landline_number'];
            $customer->service_id = $freshData['service_id'];
            $customer->service_name = Str::title($freshData['service_name']);
            $customer->domain_name = $freshData['domain_name'];
            $customer->company_website = $freshData['company_website'];
            $customer->contracted_date = $freshData['contracted_date'];
            $customer->contract_end_date = $freshData['contract_end_date'];
            $customer->contract_end_date_ad = $freshData['contract_end_date_ad'];
            $customer->contracted_by = Str::title($freshData['contracted_by']);
            $customer->remarks = $freshData['remarks'];
            $result = $customer->save();

            if($result){
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchCustomerInfo()
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " status = 'Y'";

            if ($get['sSearch_1']) {
                $cond .= "and lower(company_name) like'%".$get['sSearch_1']."%'";
            }

            if ($get['sSearch_2']) {
                $cond .= "and lower(owner_name) like'%".$get['sSearch_2']."%'";
            }

            if ($get['sSearch_3']) {
                $cond .= "and lower(email) like'%".$get['sSearch_3']."%'";
            }

            if ($get['sSearch_4']) {
                $cond .= "and lower(mobile_number) like'%".$get['sSearch_4']."%'";
            }

            if ($get['sSearch_5']) {
                $cond .= "and lower(service_name) like'%".$get['sSearch_5']."%'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }

            $sql = "SELECT 
                        COUNT(*) OVER() as totalrecs, 
                        CT.*,
                        clog.* 
                    FROM (SELECT id AS cust_id,
                        company_name,
                        owner_name,
                        email,
                        mobile_number,
                        contract_end_date_ad,
                        service_name 
                        FROM customers WHERE $cond) AS CT
                    LEFT JOIN
                        (SELECT 
                        ccl.*, 
                        lastdate.totalcalls
                    FROM 	
                        (SELECT max(id) AS id, count(id) AS totalcalls, customer_id FROM customer_call_logs GROUP BY customer_id) AS lastdate
                    INNER JOIN 
                        customer_call_logs as ccl ON ccl.id = lastdate.id) AS clog ON CT.cust_id = clog.customer_id";

            if ($limit > -1) {
                $sql = $sql . ' limit ' . $limit . ' offset ' . $offset . '';
            }

            $result = DB::select($sql);

            $ndata = $result;
            $ndata['totalrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            $ndata['totalfilteredrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;

            return $ndata;

        } catch (Exception $e){
            return [];
        }
    }
}

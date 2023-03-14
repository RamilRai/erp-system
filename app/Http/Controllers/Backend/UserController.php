<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\{User, Profile, UserRole, Common};
use Illuminate\Database\QueryException;
use App\Traits\ImageProcessTrait;
use Exception;
use Validator;
use Storage;
use DB;
use Str;

class UserController extends Controller
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
        $this->reponse = false;
        $this->queryExceptionMessage = 'Something went wrong.';
    }

    public function index()
    {
        return view('backend.users.index');
    }

    public function userCreate(Request $request)
    {
        $post = $request->all();
        $data = [];
        if (!empty($post['id'])) {
            $data['user'] = User::where(['id'=>$post['id'], 'status'=>'Y'])->first();
        }

        return view('backend.users.create', $data);
    }

    public function generateUsername(Request $request)
    {
        try {
            $post = $request->all();
            $this->message = "Username generated successfully";

            $randomNumber = rand(0000, 9999);
            $this->response = Str::lower($post['name']).$randomNumber;

        } catch (Exception $e) {
            $this->type = "error";
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }

    public function userSubmit(UserRequest $request)
    {
        try {
            $post = $request->only(['_token', 'id', 'first_name', 'middle_name', 'last_name', 'permanent_address', 'temporary_address',
                    'email', 'phone_number', 'username', 'profile', 'updateProfile', 'gender', 'dob_bs', 'dob_ad', 'blood_group']);

            $this->message = $post['id'] == null ? "User Information Submitted Successfully." : "User Information Updated Successfully.";

            DB::beginTransaction();

            // insert into users table-1
            $user = $post['id'] == null ? new User : User::where('id', $post['id'])->first();

            $storeUser = User::storeUser($post, $user);

            // insert into profiles table-2
            $profile = $post['id'] == null ? new Profile : Profile::where('id', $post['id'])->first();
            $profile->user_id = $user->id;
            if ($request->has('profile') && $post['profile'] != null) {
                Storage::delete('public/users-profile/'.$profile->profile);
                $image = $post['profile'];
                $fileName = $image->hashName();
                $folder = 'storage/users-profile';
                $this->uploadImage($image, $folder, $fileName);
                $profile->profile = $fileName;
            }

            $storeProfile = Profile::storeProfile($post, $profile);

            // insert into user_roles table-3
            if ($post['id'] == null && $post['updateProfile'] == 'N') {
                $storeUserRole = UserRole::storeUserRole($user);
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

    public function userFetch()
    {
        $data = User::fetchUserInfo();
        $filtereddata = (@$data["totalfilteredrecs"] > 0 ? $data["totalfilteredrecs"] : @$data["totalrecs"]);
        $totalrecs = @$data["totalrecs"];
        unset($data["totalfilteredrecs"]);
        unset($data["totalrecs"]);
        $i = 0;
        $array = array();
        foreach ($data as $row) {
            $array[$i]["id"] = $i +1;
            $array[$i]["fullName"] = $row->first_name. ' ' . $row->middle_name . ' ' . $row->last_name;
            $array[$i]["email"] = $row->email;
            $array[$i]["username"] = $row->username;
            $img = '<img src="'.asset('storage/users-profile/'.$row->profile).'" alt="profile" style="height:50px;">';
            $array[$i]["profile"] = $img;
            // insert actions icons
            $action = '';
            // for edit
            $action .= '<a href="javascript:;" title="Edit Data" class="tooltipdiv editUser" style="color:blue;" data-id="' . $row->id .  '"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>';
            // for delete
            $action .= '<a href="javascript:;" title="Delete Data" class="tooltipdiv deleteUser px-2" style="color:red;" data-id="' . $row->id .  '"><i class="fa-solid fa-trash-can fa-2x"></i></a>';
            // for show
            $action .= '<a href="javascript:;" title="View Data" class="tooltipdiv viewUser" style="color:green;" data-id="' . $row->id .  '"><i class="fa-solid fa-eye fa-2x"></i></a>';
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

    public function userView(Request $request)
    {
        $post = $request->all();
        $data['result'] = User::viewUserData($post);

        return view('backend.users.view', $data);
    }

    public function userDelete(Request $request)
    {
        try{
            $post = $request->all();
            $this->message = 'Data deleted successfully.';

            $result = User::deleteUserData($post);
            if (!$result)
                throw new Exception ("Couldn't process request.", 1);

        } catch (QueryException $e) {
            $this->type = 'error';
            $this->message = $this->queryExceptionMessage;
        } catch(Exception $e) {
            $this->type = 'error';
            $this->message = $e->getMessage();
        }
        Common::getJsonData($this->type, $this->message, $this->response);
    }
}

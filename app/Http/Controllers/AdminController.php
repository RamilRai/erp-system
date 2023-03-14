<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\{User, Role, UserRole};
use App\Http\Requests\AdminRequest;
use App\Http\Requests\SendOtpRequest;
use Auth;
use Hash;
use Str;
use Session;
use Exception;
use DB;

class AdminController extends Controller
{
    //========================================= login and logout start =========================================

    public function login()
    {
        if (Auth::user()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function checkLogin(AdminRequest $request)
    {
        try {
            $post = $request->only(['email_userName', 'password']);

            $checkEmail = Str::contains($post['email_userName'], '@');

            $userDetails = User::fetchUserDetails($post, $checkEmail);

            if(Auth::attempt(['email'=> $post['email_userName'],'password'=>$post['password'], 'status'=>'Y']) || Auth::attempt(['username'=> $post['email_userName'],'password'=>$post['password'], 'status'=>'Y'])){
                if ($userDetails['user']->first_login == null) {
                    session()->put('username',$userDetails['user']->username);
                    session()->save();
                    return redirect()->route('admin.otp');
                }

                if($userDetails['userRole']->role_id == 1){
                    return redirect()->route('superadmin.dashboard', session('username'))->with('success', 'You have log in successfully');
                }elseif($userDetails['userRole']->role_id == 2){
                    return redirect()->route('technical.dashboard', session('username'))->with('success', 'You have log in successfully');
                }else{
                    return redirect()->route('admin.dashboard', session('username'))->with('success', 'You have log in successfully');
                }
            }else{
                if ($userDetails['user'] == null) {
                    return redirect()->route("admin.login")->with('error', 'User Detail & Password does not match');
                } else {
                    return redirect()->route("admin.login")->with('error', 'Password does not match');
                }
            }
        }
        catch (QueryException $qe) {
            $type = 'error';
            $message = 'Something went wrong.';
            $data = false;
        }
        catch (Exception $e) {
            $type = "error";
            $message = $e->getMessage();
            $data = false;
        }
    }

    public function otp()
    {
        return view('admin.otp');
    }

    public function checkOtp(Request $request)
    {
        $post = $request->all();
        $request->validate([
            'otp' => 'required'
        ]);
        $user = User::where('username', $post['username'])->first();
        $userDetails = UserRole::where('user_id', $user->id)->select('role_id')->first();
        if ($post['otp'] == $user->otp) {
            $status = User::where('username', $post['username'])->update(['first_login'=>now()]);

            session()->put('username',$user->username);
            session()->save();

            if($userDetails->role_id == 1){
                return redirect()->route('superadmin.dashboard', session('username'))->with('success', 'You have log in successfully');
            }elseif($userDetails->role_id == 2){
                return redirect()->route('technical.dashboard', session('username'))->with('success', 'You have log in successfully');
            }else{
                return redirect()->route('admin.dashboard', session('username'))->with('success', 'You have log in successfully');
            }
        } else {
            return redirect()->route('admin.otp')->with('error', 'OTP does not match');
        }

    }

    public function superadmindashboard()
    {
        return view('admin.superadmindashboard');
    }


    public function technicaldashboard()
    {
        return view('admin.technicaldashboard');
    }

    public function admindashboard()
    {
        return view('admin.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'You have log out successfully');
    }

    //========================================= login and logout end =========================================

    //========================================= profile start =========================================

    public function adminProfile()
    {
        $data = [];
        $data['user'] = Auth::user();
        return view('admin.profile', $data);
    }

    public function fetchProfile(Request $request)
    {
        try {
            $type = "success";
            $message = "Profile fetched successfully";
            $data = [];
            $data['user'] = Auth::user();
            $data['profile'] = DB::table('profiles')->where('user_id', $data['user']->id)->first();
            if (!$data['profile'])
                throw new Exception ('No profile found.', 1);

        } catch (QueryException $e) {
            $type = 'error';
            $message = 'Something went wrong.';
            $data = false;
        } catch (Exception $e) {
            $type = "error";
            $message = $e->getMessage();
            $data = false;
        }
        echo json_encode(array("type" => $type, "message" => $message, "data" => $data));
        exit;
    }

    //========================================= profile end =========================================

    //========================================= change password by sending otp start =========================================

    public function changePassword()
    {
        return view('admin.password-change');
    }

    public function checkCurrentPassword(Request $request)
    {
        try {
            $type = 'success';
            $message = 'Current password matched.';
            $user = User::where('id', $request->user_id)->first();

            if(!(Hash::check($request->current_password, $user->password))) {
                throw new Exception('Sorry! current password does not matched.', 1);
            }

        } catch (Exception $e) {
            $type = 'error';
            $message = $e->getMessage();
        }
        echo json_encode(array("type" => $type, "message" => $message));
        exit;
    }

    public function sendOtp(SendOtpRequest $request)
    {
        try{
            $type = 'success';
            $message = 'Otp sent to your mail. please check your mail.';

            $user = User::sendOtp($request);

            $userId = $user->id;

        } catch(Exception $e) {
            $type = 'error';
            $message = $e->getMessage();
            $userId = '';
        }
        echo json_encode(array("type" => $type, "message" => $message, 'userId' => $userId));
        exit;
    }

    public function verifyOtp(Request $request)
    {
        try{
            $type = 'success';
            $message = 'OTP matched. Now you can update your password.';

            $otp = User::where('id', $request->userId)->first();

            if($otp->otp != $request->otp){
                throw new Exception('Sorry! OTP does not match.', 1);
            }

        } catch(Exception $e){
            $type = 'error';
            $message = $e->getMessage();
        }
        echo json_encode(array("type" => $type, "message" => $message));
        exit;
    }

    public function updatePassword(Request $request)
    {
        try {
            $type = 'success';
            $message = 'Password Updated Successfully';

            $updatePassword = User::updatePassword($request);

        } catch (Exception $e) {
            $type = 'error';
            $message = $e->getMessage();
        }
        echo json_encode(array("type" => $type, "message" => $message));
        exit;
    }

    //========================================= change password by sending otp end =========================================
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Arr;
use Hash;
use Str;
use Exception;
use App\Models\{Profile, Common};
use App\Models\UserRole;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMail;
use App\Mail\OtpMail;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public static function fetchUserDetails($post, $checkEmail)
    {
        $data = [];
        if ($checkEmail) {
            $data['user'] = User::where('email', $post['email_userName'])->first();
        } else {
            $data['user'] = User::where('username', $post['email_userName'])->first();
        }

        if (!empty($data['user'])) {
            $data['userRole'] = UserRole::where('user_id', $data['user']->id)->first();
        }

        return $data;
    }

    public static function storeUser($post, $user)
    {
        try {
            Arr::forget($post, 'documents');
            $freshData = sanitizeData($post);
            //=============== check existence of username in database start ===============
            $usersArray = [];
            $getUsername = DB::table('users')->select('username')->get();
            foreach ($getUsername as $key => $value) {
                $usersArray[$value->username] = $value->username;
            }
            if (empty($freshData['id'])) {
                if (in_array($freshData['username'], $usersArray)) {
                    throw new Exception("Username already exist, please use new username", 1);
                }
            } else{
                $previousUsername = $user['username'];
                $newUsername = $freshData['username'];
                if ($previousUsername != $newUsername) {
                    if (in_array($freshData['username'], $usersArray)) {
                        throw new Exception("Username already exist, please use new username", 1);
                    }
                }
            }
            //=============== check existence of username in database end ===============
            $user->first_name = Str::title($freshData['first_name']);
            $user->username = $freshData['username'];
            $user->phone_number = $freshData['phone_number'];
            $user->email = $freshData['email'];
            if ($freshData['id'] == null) {
                Mail::to($freshData[('email')])->send(new UserMail($user));
            }
            $checkOldMail = User::where('id', $post['id'])->first()->email;
            if ($freshData['email'] != $checkOldMail) {
                $user->first_login = NULL;
            }
            if ($freshData['id'] == null && $freshData['updateProfile'] == 'N') {
                $user->password = Hash::make('password');
                $user->api_token = Str::random(24);
            }

            $result = $user->save();

            if(!$result){
                throw new Exception("Error Processing Request", 1);
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function fetchUserInfo()
    {
        try {
            $get = $_GET;
            foreach ($get as $key => $value) {
                $get[$key] = trim(strtolower(htmlspecialchars($get[$key], ENT_QUOTES)));
            }
            $cond = " U.status = 'Y'";

            if ($get['sSearch_1']) {
                $cond .= "and lower(CONCAT_WS(' ',P.first_name, P.middle_name, P.last_name)) like'%".$get['sSearch_1']."%'";
            }

            if ($get['sSearch_3']) {
                $cond .= "and lower(U.email) like'%".$get['sSearch_3']."%'";
            }

            if ($get['sSearch_4']) {
                $cond .= "and lower(U.username) like'%".$get['sSearch_4']."%'";
            }

            $offset = 0;
            if ($_GET["iDisplayLength"]) {
                $limit = $_GET['iDisplayLength'];
                $offset = $_GET["iDisplayStart"];
            }
            $sql = "SELECT
                        COUNT(*) OVER() AS totalrecs,
                        U.id,
                        P.first_name,
                        P.middle_name,
                        P.last_name,
                        P.profile,
                        U.email,
                        U.username
                    FROM
                        users as U
                    JOIN
                        profiles as P
                    ON
                        U.id = P.user_id
                    WHERE $cond AND U.id > 3
                    ORDER BY U.id
                    DESC";

            // $sql = DB::table('users')->select('')
            if ($limit > -1) {
                $sql = $sql . ' limit ' . $limit . ' offset ' . $offset . '';
            }

            $result = \DB::select($sql);
            // dd($result);
            $ndata = $result;
            $ndata['totalrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            $ndata['totalfilteredrecs'] = @$result[0]->totalrecs?$result[0]->totalrecs:0;
            return $ndata;

        } catch (Exception $e){
            return [];
        }
    }

    public static function deleteUserData($post)
    {
        try {
            $data = User::where(['id'=> $post['id']])->update(['status'=>'N']);
            $data = Profile::where(['id'=> $post['id']])->update(['status'=>'N']);

            if ($data) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function viewUserData($post)
    {
        $data = User::where('id', $post['id'])->first();
        return $data;
    }

    public static function sendOtp($request)
    {
        try {
            if ($request->has('email')) {
                $user = User::where('email', $request->email)->first();
            } else {
                $user = User::where('id', $request->userid)->first();
            }

            $digit = rand(010101, 999999);
            $user->otp = $digit;
            $user->save();

            Mail::to($user->email)->send(new OtpMail($digit));

            if (!$user) {
                throw new Exception("Error Processing Request", 1);
            }

            return $user;

        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function updatePassword($request)
    {
        try {
            if ($request->has('email')) {
                $user = User::where('email', $request->email)->first();
            } else {
                $user = User::where('id', $request->user_id)->first();
            }
            $user->password = Hash::make($request->new_password);
            $result = $user->save();

            if (!$result) {
                throw new Exception('Sorry! There was problem while updating password.', 1);
            }

            return $result;

        } catch (Exception $e) {
            throw $e;
        }
    }
}

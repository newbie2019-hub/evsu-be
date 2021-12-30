<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Http\Requests\UserRequest;
use App\Mail\VerifyEmail;
use App\Models\ActivityLog;
use App\Models\ApplicantInfo;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\OfficialStudent;
use App\Models\TesUser;
use App\Models\UserSchoolYear;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store', 'approve', 'destroy', 'verifyEmail', 'delete']]);
    }

    public function store(UserRequest $request)
    {
        $hasRecord = OfficialStudent::where('student_number', $request->student_id)->first();
        $status = $hasRecord ? $hasRecord->type : 'Unofficial';

        $tesGrantee = TesUser::where('student_number', $request->student_id)->first();
        $tes_status = $tesGrantee ? $tesGrantee->type : 'No record found';

        $data = [
            'image' => $request->img,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number1,
            'contact_number2' => $request->contact_number2,
            'tes_award' => $request->tes_award,
            'tes_application_number' => $request->tes_application_number,
            'tes_grant_type' => $request->tes_grant_type,
            'street' => $request->street,
            'barangay' => $request->barangay,
            'town' => $request->town,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'birthday' => $request->birthday,   
            'program' => $request->program,
            'year_level' => $request->year_level
        ];

        $userinfo = UserInfo::create($data);

        //Create token for email verification
        $token = Str::random(26);
        $request['token'] = $token;
        
        $user = [
            'student_number' => $request->student_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_info_id' => $userinfo->id,
            'enrollment_status' => $status,
            'tes_status' => $tes_status,
            'remember_token' => $token
        ];

        $user = User::create($user);

        foreach($request->schoolyearinfo as $schoolinfo){
            if(!empty($schoolinfo['schoolyear'])){
                UserSchoolYear::create([
                    'user_id' => $user->id,
                    'semester' => $schoolinfo['semester'],
                    'units' => $schoolinfo['units'],
                    'school_year' => $schoolinfo['schoolyear'],
                    'gwa' => $schoolinfo['gwa'],
                ]);
            }
        }

        $request['id'] = $user->id;
        Mail::to($request->email)->send(new VerifyEmail($request->all()));

        return $this->success('Account created successfully');
    }

    public function verifyEmail(Request $request){
        $user = User::where('id', $request->id)->where('remember_token', $request->token)->first();
        $user->update(['email_verified_at' => Carbon::now(), 'remember_token' => '']);

        return $this->success('Email verification successful');
    }

    public function login(Request $request)
    {
        $email = User::where('email', $request->email)->whereNull('email_verified_at')->first();
        if($email){
            return response()->json(['msg' => 'Please verify your email'], 401);
        }
        else {
            $user = User::where('email', $request->email)->where('status',  'Pending')->first();
            
            if(empty($user)){
                if (! $token = auth()->guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    $log = User::where('email', $request->email)->first();
                    
                    if($log){
                        ActivityLog::create([
                            'log_name' => 'User Login Failed',
                            'event' => 'login',
                            'user_type' => 'User',
                            'user_id' => $log->id,
                            'description' => 'User account attempted to login'
                        ]);
                    }
                    else {
                        ActivityLog::create([
                            'log_name' => 'User Login Failed',
                            'event' => 'login',
                            'user_type' => 'User',
                            'user_id' => 0,
                            'description' => 'An account with an email of '.$request->email . ' attempted to login' 
                        ]);
                    }

                    return response()->json(['msg' => 'Unauthorized'], 401);
                }
            }
            else {
                return response()->json(['msg' => 'Your account is still pending'], 401);
            }
        }

        ActivityLog::create([
            'log_name' => 'User Login',
            'event' => 'login',
            'user_type' => 'User',
            'user_id' => auth('api')->user()->id,
            'description' => 'User account logged in successfully'
        ]);

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = User::with(['info', 'schoolinfo'])->where('id', auth()->guard('api')->user()->id)->first();
        return response()->json($user);
    }

    public function logout()
    {
        ActivityLog::create([
            'log_name' => 'User Logout',
            'event' => 'logout',
            'user_type' => 'User',
            'user_id' => auth('api')->user()->id,
            'description' => 'User account logged out successfully'
        ]);

        auth()->logout();
        return response()->json(['message' => 'User logged out successfully!']);
    }

    public function update(Request $request){
        if(!Hash::check($request->confirm_password, $request->user('api')->password)){
            return response()->json(['msg' => 'Incorrect Password'], 500);
        }
        else {
            
            $data = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'ext_name' => $request->ext_name,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number1,
                'contact_number2' => $request->contact_number2,
                'tes_award' => $request->tes_award,
                'tes_application_number' => $request->tes_application_number,
                'tes_grant_type' => $request->tes_grant_type,
                'street' => $request->street,
                'barangay' => $request->barangay,
                'town' => $request->town,
                'province' => $request->province,
                'zipcode' => $request->zipcode,
                'birthday' => $request->birthday,   
                'program' => $request->program,
                'year_level' => $request->year_level
            ];

            $account_info = UserInfo::where('id', auth('api')->user()->id)->first();
            $account_info->update($data);

            UserSchoolYear::where('user_id', auth()->user()->id)->delete();

            foreach($request->schoolyearinfo as $schoolinfo){
                if(!empty($schoolinfo['school_year'])){
                    UserSchoolYear::create([
                        'user_id' => auth('api')->user()->id,
                        'semester' => $schoolinfo['semester'],
                        'units' => $schoolinfo['units'],
                        'school_year' => $schoolinfo['school_year'],
                        'gwa' => $schoolinfo['gwa'],
                    ]);
                }
            }    

            $account = User::where('id', auth('api')->user()->id)->first();
            if(!empty($request->password)){
                $account->update(['student_number' => $request->student_number, 'email' => $request->email, 'password'=> Hash::make($request->password)]);
            }
            else {
                $account->update(['student_number' => $request->student_number, 'email' => $request->email]);
            }
            return $this->success('Account Information updated successfully');
        }
    }

    protected function respondWithToken($token)
    {
        $user = UserInfo::where('id', auth('api')->user()->id)->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 120,
            'user_info' => $user,
            'user_account' => auth('api')->user(),
        ]);
    }

    public function approve(Request $request, $id){
        $user = User::with(['info'])->where('id', $id)->first();
        $user->update(['status' => 'Approved']);

        ActivityLog::create([
            'log_name' => 'Account Approval',
            'event' => 'approval',
            'user_type' => 'Admin',
            'user_id' => auth('admin')->user()->id,
            'description' => 'You approved the account of ' .$user->info->first_name. ' ' .$user->info->last_name
        ]);
        
        return $this->success('Account approved successfully');
    }

    public function destroy(Request $request, $id){
        User::destroy($id);
        return $this->success('Account deleted successfully');
    }
}

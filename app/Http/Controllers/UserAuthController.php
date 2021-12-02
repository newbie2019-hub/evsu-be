<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\VerifyEmail;
use App\Models\ApplicantInfo;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store', 'approve', 'destroy', 'verifyEmail', 'delete']]);
    }

    public function store(UserRequest $request)
    {

        $data = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'tes_award' => $request->tes_award,
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
            'remember_token' => $token
        ];

        $user = User::create($user);

        $request['id'] = $user->id;
        Mail::to($request->email)->send(new VerifyEmail($request->all()));

        // foreach($request->filenames as $file){
        //     UserFiles::create([
        //         'file' => $file['name'],
        //         'path' => "public/uploads/".$file['name'],
        //         'user_id' => $applicant->id
        //     ]);
        // }
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
                    return response()->json(['msg' => 'Unauthorized'], 401);
                }
            }
            else {
                return response()->json(['msg' => 'Your account is still pending'], 401);
            }
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = User::with(['info'])->where('id', auth()->guard('api')->user()->id)->first();
        return response()->json($user);
    }


    public function logout()
    {
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
            ];

            $account_info = UserInfo::where('id', auth('api')->user()->id)->first();
            $account_info->update($data);

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
        $user = User::where('id', $id)->first();
        $user->update(['status' => 'Approved']);
        
        return $this->success('Account approved successfully');
    }

    public function destroy(Request $request, $id){
        User::destroy($id);
        return $this->success('Account deleted successfully');
    }
}

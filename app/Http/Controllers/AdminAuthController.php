<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\AdminInfo;
use App\Models\Applicant;
use App\Models\Update;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'export']]);
    }

    public function export(Request $request) 
    {
        return Excel::download(new AccountsExport($request->status), 'accounts.xlsx');
    }

    public function dashboard(){
        $accounts = User::count();
        $records = Applicant::count();
        $updates = Update::count();
        return response()->json(['account' => $accounts, 'records' => $records, 'updates' => $updates]);
    }
    
    public function accounts(Request $request){
        
        if($request->status == 'All Records' || $request->status == 'undefined'){
            if($request->search){
                $user = User::whereRelation('info', 'first_name', 'like', '%'.$request->search.'%')
                ->orWhereRelation('info', 'last_name', 'like', '%'.$request->search.'%')
                ->with(['info', 'schoolinfo'])->get()->sortBy(['info.first_name', 'DESC']);
            }
            else {
                $user = User::with(['info', 'schoolinfo'])->get()->sortBy(['info.first_name', 'DESC']);
            }
        }

        if($request->status == 'Officially Enrolled'){
            if($request->search){
                $user = User::where('enrollment_status', 'Official')->whereRelation('info', 'first_name', 'like', '%'.$request->search.'%')
                ->orWhereRelation('info', 'last_name', 'like', '%'.$request->search.'%')
                ->with(['info', 'schoolinfo'])->get()->sortBy(['info.first_name', 'DESC']);
            }
            else {
                $user = User::where('enrollment_status', 'Official')->with(['info', 'schoolinfo'])->get()->sortBy(['info.first_name', 'DESC']);
            }
        }

        if($request->status == 'Unofficial'){
            if($request->search){
                $user = User::where('enrollment_status', 'Unofficial')->whereRelation('info', 'first_name', 'like', '%'.$request->search.'%')
                ->orWhereRelation('info', 'last_name', 'like', '%'.$request->search.'%')
                ->with(['info', 'schoolinfo'])->get()->sortBy(['info.first_name', 'DESC']);
            }
            else {
                $user = User::where('enrollment_status', 'Unofficial')->with(['info', 'schoolinfo'])->get()->sortBy(['info.first_name', 'DESC']);
            }
        }
        return response()->json($user);
    }

    public function login(Request $request)
    {

        $admin = Admin::where('email', $request->email)->first();
        if (! $token = auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            
            ActivityLog::create([
                'log_name' => 'Admin Login Failed',
                'event' => 'login',
                'user_type' => 'Admin',
                'user_id' => $admin->id,
                'description' => 'Administrator account attempted to login'
            ]);

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        ActivityLog::create([
            'log_name' => 'Admin Login Success',
            'event' => 'login',
            'user_type' => 'Admin',
            'user_id' => auth('admin')->user()->id,
            'description' => 'Administrator account logged-in successfully'
        ]);

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = Admin::with(['info'])->where('id', auth()->guard('admin')->user()->id)->first();
        return response()->json($user);
    }

    public function logout()
    {
        ActivityLog::create([
            'log_name' => 'Admin Logout',
            'event' => 'logout',
            'user_type' => 'Admin',
            'user_id' => auth('admin')->user()->id,
            'description' => 'Administrator account logged out successfully'
        ]);
        
        auth()->logout();
        return response()->json(['message' => 'User logged out successfully!']);
    }

    public function update(Request $request){
        if(!Hash::check($request->confirm_password, $request->user('admin')->password)){
            return response()->json(['msg' => 'Incorrect Password'], 500);
        }
        else {
            $data = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
            ];

            $account_info = AdminInfo::where('id', auth('admin')->user()->id)->first();
            $account_info->update($data);

            $account = Admin::where('id', auth('admin')->user()->id)->first();
            if(!empty($request->password)){
                $account->update(['email' => $request->email, 'password'=> Hash::make($request->password)]);
            }
            else {
                $account->update(['email' => $request->email]);
            }
            return $this->success('Account Information updated successfully');
        }
    }

    public function changePassword(Request $request){
        if(!Hash::check($request->current_password, $request->user('admin')->password)){
            return response()->json(['msg' => 'Incorrect Password'], 422);
        }
        else {
            $account = Admin::where('id', auth('admin')->user()->id)->first();
            $account->update(['password' => Hash::make($request->new_password)]);

            return response()->json(['msg' => 'Account password changed successfully']);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        $user = AdminInfo::where('id', auth('admin')->user()->id)->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'user_info' => $user,
            'user_account' => auth('admin')->user(),
        ]);
    }
}

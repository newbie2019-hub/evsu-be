<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        if (! $token = auth()->guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = User::with(['info','status', 'familyinfo'])->where('id', auth()->guard('api')->user()->id)->first();
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
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
                'birthday' => $request->birthday
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
}

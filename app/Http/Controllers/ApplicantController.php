<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Scholarship;
use App\Models\User;
use App\Models\UserFamilyInfo;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApplicantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['store']]);
    }

    public function index()
    {
        return response()->json(User::with(['familyinfo', 'info', 'status'])->get());
    }

    public function store(UserRequest $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number,
            'street' => $request->street,
            'barangay' => $request->barangay,
            'town' => $request->town,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'has_disability' => $request->hasDisability,
            'birthday' => $request->birthday,   
            'program' => $request->program,
            'year_level' => $request->year_level
        ];

        $userinfo = UserInfo::create($data);

        $familyInfo = [
            'fathers_first_name' => $request->father_first_name,
            'fathers_middle_name' => $request->father_middle_name,
            'fathers_last_name' => $request->father_last_name,
            'mothers_first_name' => $request->mother_first_name,
            'mothers_maiden_name' => $request->mother_maiden_name,
            'mothers_last_name' => $request->mother_last_name,
            'mothers_monthly_salary' => $request->mother_monthly_salary,
            'fathers_monthly_salary' => $request->father_monthly_salary,
            'siblings_monthly_salary' => $request->siblings_monthly_salary,
            'dswd_household_number' => $request->household_number,
            'house_member' => $request->house_member,
        ];

        $userfam = UserFamilyInfo::create($familyInfo);

        $user = [
            'student_number' => $request->student_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_info_id' => $userinfo->id,
            'user_family_info_id' => $userfam->id,

        ];

        $applicant = User::create($user);
        Scholarship::create([
            'user_id' => $applicant->id,
            'status' => $request->isQualified,
        ]);

        return $this->success('Student TES Application successfully submitted');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        User::destroy($id);
        return $this->success('Applicant deleted successfully');
    }
}

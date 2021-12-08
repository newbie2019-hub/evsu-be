<?php

namespace App\Http\Controllers;

use App\Imports\OfficialStudentImport;
use App\Mail\ApplicantEmailVerification;
use App\Models\Applicant;
use App\Models\ApplicantInfo;
use App\Models\OfficialStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ApplicantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['store', 'upload', 'download', 'verifyEmail', 'export']]);
    }

    public function index(Request $request)
    {
        if($request->status == 'All Records' || $request->status == 'undefined'){
            if($request->search){
                return response()->json(
                    Applicant::whereRelation('info', 'first_name', 'like', '%'.$request->search.'%')
                    ->with(['info'])
                    ->get());
            }
            else {
                return response()->json(Applicant::with(['info'])->get());
            }
        }
        if($request->status == 'Officially Enrolled'){
            if($request->search){
                return response()->json(Applicant::whereRelation('info', 'first_name', 'like', '%'.$request->search.'%')
                ->where('status', 'Official')->with(['info'])->get());
            }
            else {
                return response()->json(Applicant::where('status', 'Official')->with(['info'])->get());
            }
        }
        if($request->status == 'Unofficial'){
            if($request->search){
                return response()->json(Applicant::whereRelation('info', 'first_name', 'like', '%'.$request->search.'%')
                ->where('status', 'Unofficial')->with(['info'])->get());
            }
            else {
                return response()->json(Applicant::where('status', 'Unofficial')->with(['info'])->get());
            }
        }
    }

    public function store(Request $request)
    {

        $hasRecord = OfficialStudent::where('student_number', $request->student_number)->first();
        $status = $hasRecord ? 'Official' : 'Unofficial';
        
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
            'academic_units' => $request->units,
            'gwa' => $request->gwa,
            'barangay' => $request->barangay,
            'town' => $request->town,
            'province' => $request->province,
            'zipcode' => $request->zipcode,
            'birthday' => $request->birthday,   
            'program' => $request->program,
            'year_level' => $request->year_level
        ];

        $applicantinfo = ApplicantInfo::create($data);

        $applicant = [
            'student_number' => $request->student_id,
            'email' => $request->email,
            'email_secondary' => $request->email_secondary,
            'applicant_info_id' => $applicantinfo->id,
            'status' => $status
        ];

        $applicant = Applicant::create($applicant);

        $this->sendMail($applicant->id, 'primary', $request);
        $this->sendMail($applicant->id, 'secondary', $request);

        return $this->success('Student TES Application successfully submitted');
    }

    public function verifyEmail(Request $request){

        if($request->type == 'primary'){
            $user = Applicant::where('id', $request->id)->where('remember_token', $request->token)->first();
            $user->update(['email_verified_at' => Carbon::now(), 'remember_token' => '']);
        }
        else {
            $user = Applicant::where('id', $request->id)->where('remember_token_secondary', $request->token)->first();
            $user->update(['email_secondary_verified_at' => Carbon::now(), 'remember_token_secondary' => '']);
        }

        return $this->success('Email verification successful');
    }


    public function sendMail($id, $type, $request){
        $token = Str::random(26);
        $request['token'] = $token;
        $request['type'] = $type;
        $request['id'] = $id;

        if($type == 'secondary'){
            $applicant = Applicant::where('id', $id)->first();
            $applicant->update(['remember_token_secondary' => $token]);
            Mail::to($request->email_secondary)->send(new ApplicantEmailVerification($request->all()));
        }
        else {
            $applicant = Applicant::where('id', $id)->first();
            $applicant->update(['remember_token' => $token]);
            Mail::to($request->email)->send(new ApplicantEmailVerification($request->all()));
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function upload(Request $request){
        if($request->filechecking){
            $fileName = time().'.'.$request->filechecking->extension();

            $import = new OfficialStudentImport;

            $file = $request->filechecking->move(public_path('crosscheck'), $fileName);
            Excel::import($import, public_path('crosscheck') . '/' .$fileName);
            $row = $import->getRowCount();

            $officials = OfficialStudent::all();
            $applicants = Applicant::all();

            foreach($applicants as $applicant){
                foreach($officials as $official){
                    if($applicant['student_number'] == $official['student_number']){
                        $applicant->update(['status' => 'Official']);
                    }
                }
            }
            
            return response()->json(['msg' => 'Cross-checking file imported successful. '.$row.' records were imported']);
        }
    }

    public function download(Request $request){
        return response()->download(storage_path('app\\public\\uploads\\'.$request->file));
    }

    public function destroy($id)
    {
        User::destroy($id);
        return $this->success('User Account deleted successfully');
    }

    public function export(Request $request) 
    {
        return Excel::download(new UsersExport($request->status), 'records.xlsx');
    }
}

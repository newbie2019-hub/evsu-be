<?php

namespace App\Exports;

use App\Models\Applicant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithProperties;

class UsersExport implements FromView, WithProperties
{
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if($this->status == 'All Records' || $this->status == 'undefined'){
            return view('records', [
                'applicants' => Applicant::with(['info'])->get()
            ]);
        }

        if($this->status == 'Officially Enrolled'){
            return view('records', [
                'applicants' => Applicant::where('status', 'Official')->with(['info'])->get()
            ]);
        }

        if($this->status == 'Unofficial'){
            return view('records', [
                'applicants' => Applicant::where('status', 'Unofficial')->with(['info'])->get()
            ]);
        }
    }

    public function properties(): array
    {
        return [
            'creator'        => 'EVSU - TES',
            'title'          => 'Records Data',
            'description'    => 'records from the server database',
            'subject'        => 'TES Records',
            'keywords'       => 'records,export,spreadsheet',
            'category'       => 'records',
            'company'        => 'EVSU',
        ];
    }
}

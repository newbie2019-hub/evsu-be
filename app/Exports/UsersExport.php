<?php

namespace App\Exports;

use App\Models\Applicant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithProperties;

class UsersExport implements FromView, WithProperties
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('records', [
            'applicants' => Applicant::with(['info'])->get()
        ]);
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

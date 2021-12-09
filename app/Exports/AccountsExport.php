<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithProperties;

class AccountsExport implements FromView, WithProperties
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
            return view('accounts', [
                'accounts' => User::with(['info', 'schoolinfo'])->get()
            ]);
        }

        if($this->status == 'Officially Enrolled'){
            return view('accounts', [
                'accounts' => User::where('enrollment_status', 'Official')->with(['info','schoolinfo'])->get()
            ]);
        }

        if($this->status == 'Unofficial'){
            return view('accounts', [
                'accounts' => User::where('enrollment_status', 'Unofficial')->with(['info','schoolinfo'])->get()
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

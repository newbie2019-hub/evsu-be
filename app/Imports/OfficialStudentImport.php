<?php

namespace App\Imports;

use App\Models\OfficialStudent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithUpserts;

HeadingRowFormatter::default('none');

class OfficialStudentImport implements ToModel, WithHeadingRow, WithUpserts
{
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $rows = 0;
    public function model(array $row)
    {
        ++$this->rows;
        return new OfficialStudent([
            'student_number' => $row['Student ID'],
            'given_name' => $row['Given Name'],
            'middle_name' => $row['Middle Name'],
            'last_name' => $row['Last Name'],
            'gender' => $row['Gender'],
            'program' => $row['Program'],
        ]);
    }

    public function uniqueBy()
    {
        return 'student_number';
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}

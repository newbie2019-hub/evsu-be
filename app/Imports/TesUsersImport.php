<?php

namespace App\Imports;

use App\Models\TesUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithUpserts;

HeadingRowFormatter::default('none');

class TesUsersImport implements ToModel, WithHeadingRow, WithUpserts
{
    private $type, $rows = 0;

    public function __construct(string $type) 
    {
        $this->type = $type;
    }

    public function model(array $row)
    {
        ++$this->rows;
        return new TesUser([
            'student_number' => $row['Student ID'],
            'given_name' => $row['Given Name'],
            'middle_name' => $row['Middle Name'],
            'last_name' => $row['Last Name'],
            'gender' => $row['Gender'],
            'program' => $row['Program'],
            'type' => $this->type
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

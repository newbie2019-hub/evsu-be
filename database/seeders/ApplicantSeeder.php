<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\ApplicantInfo;
use Illuminate\Database\Seeder;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'student_number' => '2018 - 9842758231',
            'email' => 'sampleemail@gmail.com',
            'email_verified_at' => now(),
            'email_secondary' => 'sampleemail_secondary@gmail.com',
            'email_secondary_verified_at' => now(),
            'status' => 'Approved',
            'applicant_info_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $info = [
            'first_name' => 'Yvan',
            'middle_name' => 'Caindoy',
            'last_name' => 'Sabay',
            'ext_name' => 'N/A',
            'contact_number' => '09123456789',
            'contact_number2' => '09353236789',
            'program' => '(BTLEd) - Bachelor of Technology and Livelihood Education',
            'year_level' => 'II',
            'tes_award' => 'TES - 87347123543',
            'tes_application_number' => 'TES - 82374879123',
            'tes_grant_type' => 'Listahanan',
            'street' => 'Mars Alley',
            'barangay' => 'Brgy. 109-A',
            'province' => 'Leyte',
            'zipcode' => '6500',
            'gender' => 'Male',
            'birthday' => '09-23-1999',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        ApplicantInfo::create($info);
        Applicant::create($data);
    }
}

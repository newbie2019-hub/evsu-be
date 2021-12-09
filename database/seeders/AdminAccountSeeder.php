<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account_info = AdminInfo::create([
            'first_name' => 'Darwin',
            'middle_name' => 'Gadaingan',
            'last_name' => 'Navarrosa',
            'gender' => 'Male',
            'contact_number' => '09667365117',
        ]);

        Admin::create([
            'email' => 'darwin.navarrosa@evsu.edu.ph',
            'password' => Hash::make('123123'),
            'admin_info_id' => $account_info->id
        ]);

        $account_info1 = AdminInfo::create([
            'first_name' => 'Kate Syrelle',
            'middle_name' => 'Ballais',
            'last_name' => 'DelaPena',
            'gender' => 'Female',
            'contact_number' => '09123456789',
        ]);

        Admin::create([
            'email' => 'kateballais@gmail.com',
            'password' => Hash::make('evsu123TES!'),
            'admin_info_id' => $account_info1->id
        ]);

    }
}

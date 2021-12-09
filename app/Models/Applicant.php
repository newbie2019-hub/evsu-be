<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    public $guarded = [];

    protected $hidden = [
        'remember_token',
        'remember_token_secondary',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function info(){
        return $this->belongsTo(ApplicantInfo::class, 'applicant_info_id', 'id');
    }

    public function schoolinfo(){
        return $this->hasMany(ApplicantSchoolYear::class, 'applicant_id', 'id');
    }
}

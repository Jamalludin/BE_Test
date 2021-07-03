<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = "candidate";
    protected $dates = ['date_of_birth'];

    protected $fillable = [
        'id',
        'name',
        'gender',
        'city_of_birth',
        'date_of_birth',
        'religion_id',
        'email',
        'phone',
        'identity_number',
        'identity_file',
        'bank_id',
        'bank_account',
        'bank_name',
        'address',
        'education_id',
        'university_id',
        'university_other',
        'major',
        'graduation_year',
        'in_college',
        'semester',
        'skill',
        'file_cv',
        'file_photo',
        'file_portfolio',
        'source_information_id',
        'source_information_other',
        'ranking',
        'assessment_score',
        'mail_sent',
        'instagram',
        'twitter',
        'linkedin',
        'facebook',
        'candidate_status_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = "candidate_organization";

    protected $fillable = [
        'id',
        'candidate_id',
        'org_name',
        'year',
        'position',
        'description',
        'file',
    ];
}

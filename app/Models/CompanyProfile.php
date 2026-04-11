<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'about',
        'vision_mission',
        'whatsapp',
        'email',
        'address',
        'instagram',
    ];
}

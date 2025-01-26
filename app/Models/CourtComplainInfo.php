<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtComplainInfo extends Model
{
    use HasFactory;
    public $timestamps = false;
    // protected $table = 'court_complain_infos'; // Adjust as necessary

    // Specify the fillable properties for mass assignment
    protected $fillable = [
        'magistrate_id', // Add this line
        'requisition_id',
        'complain_id',
        'complain_type',
        'comments',
        'complain_status',
        'office_name',
        'user_idno',
        'update_date',
        'update_by',
    ];
}

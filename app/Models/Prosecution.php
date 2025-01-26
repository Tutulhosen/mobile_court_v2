<?php

namespace App\Models;

use App\Models\User;
use App\Models\Court;
use App\Models\Punishment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prosecution extends Model
{
    use HasFactory;
    protected $guarded=[];

//     protected $fillable =
//     [
//     'orderSheet_id',
//     'subject',
//     'date',
//     'time',
//     'location',
//     'hints',
//     'court_id',
//     'is_suomotu',
//     'hasCriminal',
//     'case_status' ,
//     'prosecutor_details',
//     'prosecutor_name',
//     'witness1_name' ,
//     'witness1_custodian_name',
//     'witness1_mother_name',
//     'witness1_mobile_no',
//     'witness1_nationalid',
//     'witness1_address',
//     'witness1_imprint1',
//     'witness1_imprint2',
//     'witness2_name',
//     'witness2_custodian_name',
//     'witness2_mother_name',
//     'witness2_mobile_no',
//     'witness2_nationalid',
//     'witness2_address',
//     'witness2_imprint1',
//     'witness2_imprint2',
//     'requisition_id',
//     'is_approved' ,
//     'delete_status' ,
//     'case_no',
//     'divid' ,
//     'zillaId',
//     'location_type' ,
//     'upazilaid',
//     'geo_metropolitan_id',
//     'geo_citycorporation_id',
//     'geo_thana_id' ,
//     'geo_ward_id' ,
//     'prosecutor_id',
//     'witness1_age',
//     'witness2_age',
//     'case_type1',
//     'case_type2',
//     'occurrence_type',
//     'occurrence_type_text',
//     'no_criminal',
//     'is_altered',
//     'is_sizeddispose',
//     'dispose_detail',
//     'jimmader_name',
//     'jimmader_address',
//     'jimmader_designation',
//     'is_attached',
//     'user_idno',
//     'magistrate_id',
//     'location_type_old',
//     'created_by',
//     'update_by',
//     'created_at',
//     'updated_at'
// ];

    public function punishments()
    {
        return $this->hasMany(Punishment::class);

    }

    public function court()
    {
        return $this->belongsTo(Court::class)->where('status',2);
    }
    public function userinfo()
    {
        return $this->belongsTo(User::class);
    }

    public function seizurelists()
    {
        return $this->hasMany(Seizurelist::class);

    }

    // public function courtinfo()
    // {
    //     return $this->belongsTo(Court::class);
    // }

}

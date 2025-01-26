<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CriminalConfessionLawsbroken;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CriminalConfession extends Model
{
    use HasFactory;


    public function lawsBroken()
    {
        return $this->hasMany(CriminalConfessionLawsbroken::class, 'CriminalConfessionID', 'id');
    }



    public function criminal()
    {
        return $this->belongsTo(Criminal::class);
    }

    
    
}

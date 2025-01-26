<?php

namespace App\Models;

use App\Models\CriminalConfession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CriminalConfessionLawsbroken extends Model
{
    use HasFactory;

    public function criminalConfession()
    {
        return $this->belongsTo(CriminalConfession::class, 'CriminalConfessionID', 'id');
    }
}

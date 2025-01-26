<?php

namespace App\Models;

use App\Models\Prosecution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Punishment extends Model
{
    use HasFactory;

    public function criminal()
    {
        return $this->belongsTo(Criminal::class);
    }

    public function criminalConfession()
    {
        return $this->hasOne(CriminalConfession::class, 'prosecution_id', 'prosecution_id')
                    ->where('criminal_id', $this->criminal_id);
    }

    public function lawsBroken()
    {
        return $this->belongsTo(LawsBroken::class, 'laws_broken_id');
    }


    public function prosecution()
    {
        return $this->belongsTo(Prosecution::class);
    }
}

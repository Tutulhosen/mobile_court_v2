<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawsBroken extends Model
{
    use HasFactory;

    public function punishment()
    {
        return $this->hasOne(Punishment::class);
    }

    // public function section()
    // {
    //     return $this->belongsTo(Section::class, 'SectionID');
    // }
}

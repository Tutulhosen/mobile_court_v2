<?php

namespace App\Models;

use App\Models\Prosecution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seizurelist extends Model
{
    use HasFactory;

    public function prosecution()
    {
        return $this->belongsTo(Prosecution::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criminal extends Model
{
    use HasFactory;

    public function punishments()
    {
        return $this->hasMany(Punishment::class);
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\Prosecution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Court extends Model
{
    use HasFactory;

    // Define the relationship
    public function prosecutions()
    {
        return $this->hasMany(Prosecution::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeTotalCourt($query)
    {
        return $query->where(['status' => 2]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'magistrate_id'); // assuming `magistrate_id` is the foreign key linking to `users` table
    }

   
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RegisterLabel;
class RegisterList extends Model
{
    use HasFactory;

    public function labels()
    {
        return $this->belongsToMany(RegisterLabel::class, 'register_list_vs_labels', 'list_id', 'label_id');
    }
}

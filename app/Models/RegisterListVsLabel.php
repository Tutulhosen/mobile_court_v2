<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterListVsLabel extends Model
{
    use HasFactory;
    public $id;
    public $list_id;
    public $label_id;
}

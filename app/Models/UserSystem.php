<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSystem extends Model
{
    use HasFactory;

    public function sessions(){
        return $this->hasMany(Session::class, "user_id","id");
    }
}

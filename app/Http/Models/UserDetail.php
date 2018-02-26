<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserDetail extends Model
{
    //
    protected $fillable = ['nickname'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

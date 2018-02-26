<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Image extends Model
{
    //
    protected $fillable = ['avatar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

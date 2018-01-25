<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function findForPassport($login) {
        return User::orWhere('email', $login)->orWhere('mobile', $login)->first();
    }

   /* public function findForPassport($login)
    {
        return $this->orWhere('phone', $login)->first();
    }*/


   /* public function findForPassport($login)
    {
        return $this->orWhere('email', $login)->orWhere('name', $login)->first();
    }*/


}

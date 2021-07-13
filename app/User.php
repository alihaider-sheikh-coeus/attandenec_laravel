<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','password','department','designation_id','is_hr','profile_pic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function attandence()
    {
        return $this->hasMany(Attandence::class,'user_id');
    }
    public function delete_user_object($user)
    {
        $response=false;
        if($user)
        {
            unlink(public_path() .  '/uploads/' . $user->profile_pic );
            $user->attandence()->delete();
            $response =(bool)($user->delete()) ;
        }
        return $response;
    }
}

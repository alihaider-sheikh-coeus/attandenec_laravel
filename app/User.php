<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

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
    public function storePicture( $request)
    {
        $cover = $request->file('profile-pic');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
        return ["cover"=>$cover,"extension"=>$extension];
    }
    public function insertUser($request,$picture)
    {
       $status= DB::table('users')->insert([
            ['email' =>$request->email,
                'name' =>$request->name,
                'password'=>bcrypt($request->password),
                'profile_pic'=> $picture["cover"]->getFilename().'.'.$picture["extension"],
                'designation_id'=>$request->designation_id,
                'is_hr'=>$request->has('is_hr')? 1:0,
                'salary'=>$request->salary,
                'boss_name'=>$request->boss_name,
                'department'=>$request->department,
                'created_at'=>Carbon::now()
            ],
        ]);
       return $status;
    }
}

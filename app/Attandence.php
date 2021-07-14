<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Attandence extends Model
{
    protected $table = 'attandence';


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function attendanceStatus($request)
    {
        $status='P';
        $hour=Carbon::parse($request->TimeIn)->format('H');

        if($request->TimeIn!=null)
        {if($hour>=11 &&  $hour<12 )
        {
            $status='L';
        } elseif ($hour>=12)
        {
            $status='A';
        } else
        {
            $status ='P';
        }
        }
        return $status;
    }
    public function updateAttendance($request,$user,$status)
    {
        $stat=  DB::table('attandence')
            ->where('user_id',$user->id)
            ->whereDate('date',Carbon::today())
            ->update(['time_in' =>$request->TimeIn,'time_out' =>$request->TimeOut,'status'=>$status]);
        return $stat;
    }
    public function markAttendance($request,$user,$status)
    {
        $stat=  DB::table('attandence')->insert([
            ['user_id' =>$user->id,
                'time_in' =>$request->TimeIn,
                'time_out' =>$request->TimeOut,
                'status' =>$status,
                'created_at'=>Carbon::now(),
                'date'=>Carbon::now()
            ],
        ]);
        return $stat;
    }
}

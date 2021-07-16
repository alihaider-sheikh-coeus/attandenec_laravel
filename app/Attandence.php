<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
        {
            if($hour>=11 &&  $hour<12 )
        {
            $status='L';
        } elseif ($hour>=12)
        {
            $status='A';
        }
 else
        {
            $status ='P';
        }
        }
        return $status;
    }
    public function updateAttendance($request,$user,$status)
    {
        $stat=  Attandence::where('user_id',$user->id)
            ->whereDate('date',Carbon::today())
            ->update(['time_in' =>$request->TimeIn,'time_out' =>$request->TimeOut,'status'=>$status]);
        return $stat;
    }
    public function markAttendance($request,$user,$status)
    {
        $stat = Attandence::insert([
            [  'user_id' =>$user->id,
                'time_in' =>$request->TimeIn,
                'time_out' =>$request->TimeOut,
                'status' =>$status,
                'created_at'=>Carbon::now(),
                'date'=>Carbon::now()
            ],
        ]);
        return $stat;
    }
    public function attandenceForToday()
    {
      return   Attandence::where('user_id',Auth::user()->id)
            ->whereDate('date',Carbon::today())
            ->get(['time_in','time_out']);
    }
    public function generateMonthlyReport($request)
    {
        $month=explode(" ",$request->month);

        switch ($month[0]) {
            case 'January':
                $month[0] = 01;
                break;
            case 'February':
                $month[0] = 02;
                break;
            case 'March':
                $month[0] = 03;
                break;
            case 'April':
                $month[0] = 04;
                break;
            case 'May':
                $month[0] = 05;
                break;
            case 'June':
                $month[0] = 4 ;
                break;
            case 'July':
                $month[0] = 7 ;
                break;
            case 'August':
                $month[0] = 8;
                break;
            case 'September':
                $month[0] = 9 ;
                break;
            case 'October':
                $month[0] =10 ;
                break;
            case 'November':
                $month[0] = 11;
                break;
            case 'December':
                $month[0] = 12;
                break;
            default:
                $month[0] = 'Not a valid month!';
                break;
        }
  $users =  Attandence::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->whereMonth('date', '=',(int)$month[0])
            ->get();
        return $users;
    }
    public function generateDailyReport()
    {
        return Attandence::join('users','attandence.user_id','=','users.id')
            ->select( DB::raw('status,time_in,time_out,users.email,name'))
            ->whereDate('date', '=',Carbon::today())
            ->get();
    }

}

<?php

namespace App\Http\Controllers;

use App\Attandence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttandenceController extends Controller
{
    public $attandence_object;
    public function __construct()
    {
        $this->attandence_object = new Attandence();
    }
    public function markattandenceView()
    {
        $attandence_for_today = DB::table('attandence')
        ->where('user_id',Auth::user()->id)
        ->whereDate('date',Carbon::today())
        ->get(['time_in','time_out']);

        return view('attandence',["attandence_check"=>$attandence_for_today]);
    }
    public function markAttandence(Request $request)
    {
        $status= $this->attandence_object->attendanceStatus($request);
        $user = Auth::user();
        $already_marked=DB::table('attandence')
            ->where('user_id',$user->id)
            ->whereDate('date',Carbon::today())
            ->get();
        if(count($already_marked) && !empty($already_marked))
        {
            $stat= $this->attandence_object->updateAttendance($request,$user,$status);
            return  ($stat)?   $this->markattandenceView() :redirect()->route('markattandence')->with('error','u have already  mark your  attendance');
        }
        else
        {
            $stat=$this->attandence_object->markAttendance($request,$user,$status);
            return  ($stat)? $this->markattandenceView() :redirect()->route('markattandence')->with('error','unable to mark attendance');
        }
    }
    public function generateReport(Request $request)
    {
        $month=explode("/",$request->month);

        $users=  DB::table('attandence')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
           ->whereMonth('date', '=',(int)$month[0])
           ->get();

        return view('HR.report',['users'=>$users]);
    }
    public function generatedailyReport(Request $request)
     {
     $users=  DB::table('attandence')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->whereDate('date', '=',Carbon::today())
            ->get();

        return view('HR.daily_report',['users'=>$users]);
    }
}

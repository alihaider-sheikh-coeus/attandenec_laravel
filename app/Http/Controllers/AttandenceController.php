<?php

namespace App\Http\Controllers;

use App\Attandence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AttandenceController extends Controller
{
    private $attandence_object;

    public function __construct()
    {
        $this->attandence_object = new Attandence();
    }
    public function markattandenceView()
    {
        $attandence_for_today = $this->attandence_object->attandenceForToday();
        return view('attandence',["attandence_check"=>$attandence_for_today]);
    }
    public function markAttandence(Request $request)
    {
        $user = Auth::user();
        $status= $this->attendanceStatus($request);
        $already_marked = $this->attandence_object->attandenceForToday();

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
     $users = $this->attandence_object->generateMonthlyReport($request);
     return view('HR.report',['users'=>$users]);
    }
    public function generatedailyReport(Request $request)
     {
             $users_from_db = $this->attandence_object->generateDailyReport();
             return view('HR.daily_report',['users'=>$users_from_db]);
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
}

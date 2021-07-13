<?php

namespace App\Http\Controllers;

use App\Attandence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttandenceController extends Controller
{
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
        $user = Auth::user();
        $already_marked=DB::table('attandence')
            ->where('user_id',$user->id)
            ->whereDate('date',Carbon::today())
            ->get();
        if(count($already_marked) && !empty($already_marked))
        {
            $stat=  DB::table('attandence')
                ->where('user_id',$user->id)
                ->whereDate('date',Carbon::today())
                ->update(['time_in' =>$request->TimeIn,'time_out' =>$request->TimeOut,'status'=>$status]);
            return  ($stat)?   $this->markattandenceView() :redirect()->route('markattandence')->with('error','unable to mark attendance');
        }
        else
        {
            $stat=DB::table('attandence')->insert([
                ['user_id' =>$user->id,
                    'time_in' =>$request->TimeIn,
                    'time_out' =>$request->TimeOut,
                    'status' =>$status,
                    'created_at'=>Carbon::now(),
                    'date'=>Carbon::now()

                ],
            ]);
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

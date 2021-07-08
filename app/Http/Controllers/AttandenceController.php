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
        return view('attandence');
    }
    public function markAttandence(Request $request)
    {
        $status='P';
        $hour=Carbon::parse($request->TimeIn)->format('H');
//        dd($hour);
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
        $stat=DB::table('attandence')->insert([
            ['user_id' =>$user->id,
                'time_in' =>$request->TimeIn,
                'time_out' =>$request->TimeOut,
                'status' =>$status,
                'created_at'=>Carbon::now(),
                'date'=>Carbon::now()

            ],
        ]);
        return  ($stat)?  "mark attandence" :"unable to mark attandence";
    }
    public function generateReport(Request $request)
    {

        $month=explode("/",$request->month);
       $users=  DB::table('attandence')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
           ->whereMonth('date', '=',(int)$month[0])
           ->get();
//       return $users;
        return view('HR.report',['users'=>$users]);
    }
}

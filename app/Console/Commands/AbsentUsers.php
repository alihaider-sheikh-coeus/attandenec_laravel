<?php

namespace App\Console\Commands;

use App\Attandence;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AbsentUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailToBoss:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to boss for user absent';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $late_users = Attandence::whereHas('user', function ($q) {
            $q->select('id', 'email', 'name','boss_id')
                ->WhereNotNull('boss_id');
        })
            ->where('attandence.status','=','A')
            ->get()
            ->pluck('user.boss_id','user.email');

        $objects = json_decode($late_users,true);
        $id_array=array();
        foreach ($objects  as $user=>$v)
        {
            array_push($id_array,$v);

        }
        $bosses_emails=  User::whereIn('boss_id',$id_array)->get()->pluck('email');
        $data = array('name'=>"xyz solutions", "body" => "your employee have been marked as absent ");
        Mail::send('mail', $data, function($mail) use ($bosses_emails)
        {
            $mail->from('ali.haider6713@gmail.com');
            foreach ($bosses_emails AS $user) {
                $mail->to($user);
            }
            $mail->subject('Attendance mark as an absent');
        });

    }
}

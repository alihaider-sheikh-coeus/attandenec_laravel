<?php

namespace App\Console\Commands;

use App\Attandence;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a  email to a user';

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
        $late_users=
            Attandence::with('user:id,email,name')
            ->where('status','=','A')
            ->get()
            ->pluck('user.email');

        $data = array('name'=>"xyz solutions", "body" => "your attendance have been marked as absent ");
        Mail::send('mail', $data, function($mail) use ($late_users)
        {
            $mail->from('ali.haider6713@gmail.com');
            foreach ($late_users AS $user) {
                $mail->to($user);
            }
            $mail->subject('Attendance reminder');
        });
    }
}

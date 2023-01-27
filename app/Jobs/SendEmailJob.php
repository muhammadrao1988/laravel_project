<?php

namespace App\Jobs;

use App\Mail\FollowOperation;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $send_mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($send_mail)
    {

        $this->send_mail = $send_mail;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::channel('cron')->info('=======Cron Refund started======');
        \Log::channel('cron')->info(json_encode($this->send_mail));
        if($this->send_mail['module']=="Follower" || $this->send_mail['module']=="Wishlist" || $this->send_mail['module']=="OrderItem"){
            \Log::channel('cron')->info('======end======');
            $email = new FollowOperation($this->send_mail);

           \Mail::to($this->send_mail['send_to'])->send($email);
            if (\Mail::failures()) {
                $response = Mail::failures();
                EmailLog::create([
                    'from' => env("MAIL_FROM_ADDRESS"),
                    'to' => $this->send_mail['send_to'],
                    'subject' => $this->send_mail['subject'],
                    'body' => json_encode($email),
                    'response' => $response,
                ]);
            }
        }

    }
}

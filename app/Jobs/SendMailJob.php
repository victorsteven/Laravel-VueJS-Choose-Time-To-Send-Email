<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $email;
    protected $emailClass;
   /**
    * Create a new job instance.
    *
    * @return void
    */

   public function __construct($email, $emailClass)
   {
       $this->email = $email;
       $this->emailClass= $emailClass;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
       Mail::to($this->email)->send($this->emailClass);
   }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'email:send-test';
    protected $description = 'Send a test email';

    public function handle()
    {
        Mail::to('test@gmail.com')->send(new TestEmail());
        $this->info('Test email sent! Check Mailtrap inbox.');
    }
}
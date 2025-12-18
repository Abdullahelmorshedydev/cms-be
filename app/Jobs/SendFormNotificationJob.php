<?php

namespace App\Jobs;

use App\Mail\FormNotificationMail;
use App\Models\Form;
use App\Models\FormEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFormNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Form $form,
        public FormEmail $recipient
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recipient->email)
            ->send(new FormNotificationMail($this->form, $this->recipient));
    }
}


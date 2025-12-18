<?php

namespace App\Jobs;

use App\Modules\Communication\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $recipients;
    public string $message;
    public array $channels;
    public array $data;

    public function __construct(array $recipients, string $message, array $channels, array $data = [])
    {
        $this->recipients = $recipients;
        $this->message = $message;
        $this->channels = $channels;
        $this->data = $data;
    }

    public function handle(NotificationService $notificationService): void
    {
        foreach ($this->recipients as $recipient) {
            $notificationService->sendMultiChannel(
                $recipient,
                $this->message,
                $this->channels,
                $this->data
            );
        }
    }
}



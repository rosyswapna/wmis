<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WorkersReportExportCompleted extends Notification
{
    use Queueable;

    public function __construct(
        public int $exportId
    ) {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Workers Report Ready',
            'message' => 'Your workers report has been generated.',
            'export_id' => $this->exportId
        ];
    }
}
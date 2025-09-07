<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifikasiBaru extends Notification
{
    use Queueable;

    protected $pesan;
    protected $url;

    public function __construct($pesan, $url)
    {
        $this->pesan = $pesan;
        $this->url   = $url;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'pesan' => $this->pesan,
            'url'   => $this->url,
        ];
    }
}

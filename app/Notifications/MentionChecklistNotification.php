<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Card;
use DB;

class MentionChecklistNotification extends Notification
{
    use Queueable;
    public $user;
    public $name;
    public $authId;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $name, $authId)
    {
        $this->user = $user;
        $this->name = $name;
        $this->authId = $authId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name'      => $this->user->name,
            'avatar'    => $this->user->avatar,
            'message'   => 'Mention Tag Checklist',
            'message2'  => 'LogHub Apps',
            'message3'  => $this->name,
            'message4'  => Session::get('name'),
            'message5'  => Carbon::now()->toDateTimeString(),
            'message6'  => $this->authId,
        ];
    }
}

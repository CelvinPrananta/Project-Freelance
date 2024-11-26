<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use DB;

class UlangTahunNotification extends Notification
{
    use Queueable;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $result_pribadi = DB::table('daftar_pegawai')->find($notifiable->id);
        $result_user = User::find($notifiable->id);

        $result_tanggal_lahir = new \DateTime($result_pribadi->tgl_lahir);
        $hari_ini = new \DateTime();
        $result_usia = $result_tanggal_lahir->diff($hari_ini)->y;

        return [
            'name'      => $result_user->name,
            'avatar'    => $result_user->avatar,
            'message'  => 'Happy Birthday',
            'message2'  => 'Employee on',
            'message3'  => 'PT. Tatacipta Teknologi Indonesia',
            'message4'  => 'City of Surabaya.',
            'message5'  => 'On behalf of PT. TATI Surabaya City Wishes You a Happy Birthday the ',
            'message6'  => ''.$result_usia.'',
            'message7'  => 'May you always be given health and smoothness.'
        ];
    }
}
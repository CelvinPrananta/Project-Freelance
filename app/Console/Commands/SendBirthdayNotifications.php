<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\UlangTahunNotification;
use Illuminate\Support\Facades\DB;

class SendBirthdayNotifications extends Command
{
    protected $signature = 'birthday:notifications';
    protected $description = 'Kirim pemberitahuan ulang tahun ke pengguna';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = DB::table('daftar_pegawai')->whereRaw("DATE_FORMAT(tgl_lahir, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d')")->get();
        foreach ($users as $user) {
            $userModel = User::find($user->user_id);
            if ($userModel) {
                $userModel->notify(new UlangTahunNotification($userModel));
            }
        }
        $this->info('Notifikasi ulang tahun berhasil dikirim.');
    }
}
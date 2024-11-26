<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
// use App\Models\CompanySettings;
use App\Notifications\UlangTahunNotification;
use App\Notifications\MentionDescriptionNotification;
use App\Notifications\MentionChecklistNotification;
use App\Notifications\MentionCommentNotification;
use App\Models\Notification;
use App\Models\ModeAplikasi;
use App\Models\User;
use DB;

class HomeController extends Controller
{
    /**
     * Buat instance pengontrol baru.
     *
     * @return void
     */

    /**
     * Tampilkan dasbor aplikasi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Halaman Utama //
    public function index(Request $request)
    {
        $dataPengguna = User::where('role_name', 'User')->count();
        $dataOnline = User::where('status_online', 'Online')->count();
        $dataOffline = User::where('status_online', 'Offline')->count();

        $currentHour = date('G');
        $greetings = [
            'morning' => 'Good Morning,',
            'afternoon' => 'Good Afternoon,',
            'evening' => 'Good Evening,',
            'night' => 'Good Night,',
        ];

        if ($currentHour < 12) {
            $greeting = $greetings['morning'];
        } elseif ($currentHour < 16) {
            $greeting = $greetings['afternoon'];
        } elseif ($currentHour < 18) {
            $greeting = $greetings['evening'];
        } else {
            $greeting = $greetings['night'];
        }

        return view('dashboard.Halaman-home', compact(
            'greeting',
            'dataPengguna',
            'dataOnline',
            'dataOffline',
        ));
    }
    // Manual Function Notifikasi Ultah //
    public function ulangtahun()
    {
        if (auth()->user()) {
            $user = User::first();
            dd($user);
            $notification = auth()->user()->notifications->where('data.user_id', $user->id)->first();

            if (!$notification) {
                $notification = new UlangTahunNotification($user);
                $notification->data['user_id'] = $user->id;
                auth()->user()->notify($notification);
            }
        }
        return back();
    }
    // /Manual Function Notifikasi Ultah //

    // Untuk Mengirikan Mention Tag Notifikasi //
    public function mentionDescriptionNotification(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'keterangan' => 'required|string'
        ]);

        // Ambil username dari permintaan
        $username = $request->input('username');
        $keterangan = $request->input('keterangan');

        // Temukan pengguna berdasarkan username
        $user = User::where('username', $username)->first();

        // Kirim notifikasi jika pengguna ditemukan
        if ($user) {
            $authId = auth()->id();
            $notification = new MentionDescriptionNotification($user, $keterangan, $authId);
            $user->notify($notification);
            return response()->json(['message' => 'Notifikasi mention berhasil dikirim.']);
        } else {
            return response()->json(['error' => 'Pengguna mention tidak ditemukan.'], 404);
        }
    }

    public function mentionChecklistNotification(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'name' => 'required|string'
        ]);

        // Ambil username dari permintaan
        $username = $request->input('username');
        $name = $request->input('name');

        // Temukan pengguna berdasarkan username
        $user = User::where('username', $username)->first();

        // Kirim notifikasi jika pengguna ditemukan
        if ($user) {
            $authId = auth()->id();
            $notification = new MentionChecklistNotification($user, $name, $authId);
            $user->notify($notification);
            return response()->json(['message' => 'Notifikasi mention berhasil dikirim.']);
        } else {
            return response()->json(['error' => 'Pengguna mention tidak ditemukan.'], 404);
        }
    }

    public function mentionCommentNotification(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'content' => 'required|string'
        ]);

        // Ambil username dari permintaan
        $username = $request->input('username');
        $content = $request->input('content');

        // Temukan pengguna berdasarkan username
        $user = User::where('username', $username)->first();

        // Kirim notifikasi jika pengguna ditemukan
        if ($user) {
            $authId = auth()->id();
            $notification = new MentionCommentNotification($user, $content, $authId);
            $user->notify($notification);
            return response()->json(['message' => 'Notifikasi mention berhasil dikirim.']);
        } else {
            return response()->json(['error' => 'Pengguna mention tidak ditemukan.'], 404);
        }
    }
    // /Untuk Mengirikan Mention Tag Notifikasi //

    // Mode Tema Aplikasi //
    public function updateTemaAplikasi(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = ModeAplikasi::findOrFail($id);
            $tema_aplikasi = $request->input('tema_aplikasi');
            if ($tema_aplikasi == 'Terang') {
                $user->tema_aplikasi = 'Terang';
                $user->warna_sistem = null;
                $user->warna_sistem_tulisan = null;
                $user->warna_mode = null;
                $user->tabel_warna = null;
                $user->tabel_tulisan_tersembunyi = null;
                $user->warna_dropdown_menu = null;
                $user->ikon_plugin = null;
                $user->bayangan_kotak_header = null;
                $user->warna_mode_2 = null;
            } elseif ($tema_aplikasi == 'Gelap') {
                $user->tema_aplikasi = 'Gelap';
                $user->warna_sistem = '#171527';
                $user->warna_sistem_tulisan = 'white';
                $user->warna_mode = '#292D3E';
                $user->tabel_warna = 'rgba(0,0,0,.05)';
                $user->tabel_tulisan_tersembunyi = '#a3a3a3';
                $user->warna_dropdown_menu = 'rgb(31 28 54 / 1)';
                $user->ikon_plugin = 'rgba(244, 59, 72, 0.6)';
                $user->bayangan_kotak_header = '0 0.4px 5px rgb(255 255 255)';
                $user->warna_mode_2 = '#2b2e3c';
            }
            $user->save();

            $user2 = User::findOrFail($id);

            $tema_aplikasi = $request->input('tema_aplikasi');
            if ($tema_aplikasi == 'Terang') {
                $user2->tema_aplikasi = 'Terang';
            } elseif ($tema_aplikasi == 'Gelap') {
                $user2->tema_aplikasi = 'Gelap';
            }
            $user2->save();

            DB::commit();
            Toastr::success('Tema aplikasi berhasil diperbarui', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Tema aplikasi gagal diperbarui', 'Error');
            return redirect()->back();
        }
    }
    // /Mode Tema Aplikasi //
}

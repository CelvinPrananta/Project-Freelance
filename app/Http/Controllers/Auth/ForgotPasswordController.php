<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Mail;

class ForgotPasswordController extends Controller
{
    // Untuk Mendapatkan Email Pengguna //
    public function getEmail()
    {
       $result_name = DB::table('users')->get();
       return view('auth.passwords.email', compact('result_name'));
    }
    // /Untuk Mendapatkan Email Pengguna //

    // Kirim Email Pengguna //
    public function postEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(60);
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Nama Pengguna Tidak Ditemukan']);
        }

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'name' => $user->name,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resultEmail = $request->input('email');

        Mail::send('auth.verify', ['token' => $token], function ($message) use ($request, $resultEmail) {
            $message->from($request->email, 'Administrator | Loghub - PT TATI');
            $message->to($resultEmail);
            $message->subject('Ubah Kata Sandi | Loghub - PT TATI');
        });

        Toastr::success('Kami telah mengirimkan tautan pengaturan ulang kata sandi Anda melalui email!', 'Success');
        return redirect('login');
    }
    // /Kirim Email Pengguna //
}
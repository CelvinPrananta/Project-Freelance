<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use DB;

class RegisterController extends Controller
{
    // Tampilan Daftar Aplikasi //
    public function tampilanDaftar()
    {
        return view('auth.register');
    }
    // /Tampilan Daftar Aplikasi //

    // Daftar Pengguna Baru //
    public function daftarAplikasi(Request $request)
    {
        $request->validate([
            'username'              => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $dt        = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();
            
            User::create([
                'username'          => $request->username,
                'name'              => $request->username,
                'email'             => $request->email,
                'avatar'            => $request->image,
                'join_date'         => $todayDate,
                'role_name'         => 'User',
                'status'            => 'Active',
                'tema_aplikasi'     => 'Terang',
                'status_online'     => 'Offline',
                'password'          => Hash::make($request->password)
            ]);
            
            DB::commit();
            Toastr::success('Pendaftaran akun baru telah berhasil, silahkan login menggunakan akun anda!','Success');
            return redirect('login');
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Pendaftaran akun baru telah gagal, silahkan mendaftar ulang kembali!','Error');
            return redirect()->back();
        }
    }
    // /Daftar Pengguna Baru //
}
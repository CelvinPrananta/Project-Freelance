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
            'name'                  => 'required|string|max:255',
            'username'              => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|same:password|min:8',
        ],
        [
            'name.required' => 'Bidang nama lengkap wajib diisi.',
            'username.required' => 'Bidang nama pengguna wajib diisi.',
            'email.required' => 'Bidang email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email yang anda masukkan telah terpakai.',
            'password.required' => 'Bidang kata sandi wajib diisi.',
            'password.min' => 'Kata sandi harus minimal 8 karakter.',
            'password.confirmed' => 'Kata sandi dan konfirmasi kata sandi tidak sesuai.',
            'password_confirmation.required' => 'Bidang konfirmasi kata sandi wajib diisi.',
            'password_confirmation.min' => 'Konfirmasi kata sandi harus minimal 8 karakter.',
            'password_confirmation.same' => 'Kata sandi dan konfirmasi kata sandi tidak sesuai.',
        ]);

        try {
            $dt        = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();
            
            User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'username'          => $request->username,
                'employee_id'       => null,
                'join_date'         => $todayDate,
                'status'            => 'Inactive',
                'role_name'         => 'User',
                'avatar'            => $request->image,
                'tgl_lahir'         => null,
                'password'          => Hash::make($request->password),
                'tema_aplikasi'     => 'Terang',
                'status_online'     => 'Offline',
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
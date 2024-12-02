<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use DB;
use Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Tempat mengarahkan pengguna setelah login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Buat instance pengontrol baru.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'locked',
            'unlock'
        ]);
    }

    // Tampilan Masuk Aplikasi //
    public function login(Request $request)
    {
        return view('auth.login');
    }
    // /Tampilan Masuk Aplikasi //

    // Tampilan Landing Page //
    public function landing()
    {
        return view('auth.landing');
    }
    // /Tampilan Landing Page //

    // Untuk Cek Authentifikasi //
    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'username_employee_id_atau_email'   => 'required|string',
            'password'                          => 'required|string'
        ]);

        $username = $request->username_employee_id_atau_email;
        $password = $request->password;

        if ($username === '-') {
            Toastr::error('Gagal, Username / ID Employee / Email tidak valid. Silahkan masukkan kembali Username / ID Employee / Email valid.', 'Error');
            return redirect('login');
        }

        try {
            $dt         = Carbon::now();
            $todayDate  = $dt->toDayDateTimeString();

            // Mencoba mengautentikasi menggunakan nama pengguna, id_karyawan, atau email
            $authUsername   = Auth::attempt(['username'     => $username, 'password' => $password, 'status' => 'Active']);
            $authEmployee   = Auth::attempt(['employee_id'  => $username, 'password' => $password, 'status' => 'Active']);
            $authEmail      = Auth::attempt(['email'        => $username, 'password' => $password, 'status' => 'Active']);

            if ($authUsername || $authEmployee || $authEmail) {
                $user = Auth::user();

                // Jika pengguna aktif, simpan data sesi
                Session::put([
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'employee_id' => $user->employee_id,
                    'user_id' => $user->user_id,
                    'join_date' => $user->join_date,
                    'status' => $user->status,
                    'role_name' => $user->role_name,
                    'avatar' => $user->avatar
                ]);

                // Aktivitas log
                $activityLog = [
                    'name' => Session::get('name'),
                    'username' => $user->username,
                    'employee_id' => $user->employee_id,
                    'email' => $user->email,
                    'description' => 'Berhasil Masuk Aplikasi',
                    'date_time' => $todayDate
                ];
                DB::table('activity_logs')->insert($activityLog);

                // Perbarui status pengguna menjadi 'Online'
                DB::table('users')->where('user_id', Session::get('user_id'))->update(['status_online' => 'Online']);

                Toastr::success('Anda berhasil masuk aplikasi', 'Success');
                return redirect()->intended('home');
            }

            // Periksa jika status pengguna 'Inactive'
            $user = User::where('username', $username)->orWhere('employee_id', $username)->orWhere('email', $username)->first();
            if ($user && $user->status === 'Inactive') {
                Toastr::error('Silahkan hubungi admin, akun Anda sedang dalam tahap persetujuan', 'Error');
                return redirect('login');
            }

            // Periksa apakah username/email/employee_id ada tapi password salah
            if (User::where('username', $username)->orWhere('employee_id', $username)->orWhere('email', $username)->exists()) {
                Toastr::error('Gagal, kata sandi anda tidak sama. Silahkan masukkan kembali kata sandi valid', 'Error');
                return redirect('login');
            }

            Toastr::error('Gagal, Username / ID Employee / Email anda tidak terdaftar pada aplikasi ini', 'Error');
            return redirect('login');
        } catch (\Exception $e) {
            \Log::error($e);
            DB::rollback();
            Toastr::error('Gagal, terjadi kesalahan sistem. Silahkan coba lagi nanti', 'Error');
            return redirect()->back();
        }
    }
    // /Untuk Cek Authentifikasi //

    // Untuk Keluar Aplikasi //
    public function logout(Request $request)
    {
        $dt         = Carbon::now();
        $todayDate  = $dt->toDayDateTimeString();

        $result_user_id = Session::get('user_id');
        $updateStatus = [
            'status_online' => 'Offline'
        ];
        DB::table('users')->where('user_id', $result_user_id)->update($updateStatus);

        $activityLog = ['name' => Session::get('name'), 'username' => Session::get('username'), 'employee_id' => Session::get('employee_id'), 'email' => Session::get('email'), 'description' => 'Berhasil Keluar Aplikasi', 'date_time' => $todayDate];
        DB::table('activity_logs')->insert($activityLog);
        $request->session()->forget('name');
        $request->session()->forget('email');
        $request->session()->forget('username');
        $request->session()->forget('employee_id');
        $request->session()->forget('user_id');
        $request->session()->forget('join_date');
        $request->session()->forget('status');
        $request->session()->forget('role_name');
        $request->session()->forget('avatar');
        $request->session()->flush();

        Auth::logout();
        Toastr::success('Anda berhasil keluar aplikasi!','Success');
        return redirect('login');
    }
    // Untuk Keluar Aplikasi //
}
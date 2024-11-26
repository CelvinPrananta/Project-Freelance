<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use DB;
use Hash;

class ResetPasswordController extends Controller
{
    // Untuk Mendapatkan Token Kata Sandi //
    public function getPassword($token)
    {
       $result_name = DB::table('users')->get();
       return view('auth.passwords.reset', ['result_name' => $result_name, 'token' => $token]);
    }
    // /Untuk Mendapatkan Token Kata Sandi //

    // Untuk Perbaharui Kata Sandi //
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();
        
        if(!$updatePassword)
        {
            Toastr::error('Token tidak valid!','Error');
            return back();
        } else{ 
            
            $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['email'=> $request->email])->delete();
            Toastr::success('Kata sandi Anda telah diubah!','Success');
            return redirect('/login');
        }
    }
    // /Untuk Perbaharui Kata Sandi //
}
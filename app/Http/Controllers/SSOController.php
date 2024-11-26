<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SSOController extends Controller
{
    public function callback(Request $request)
    {
        $client = new \GuzzleHttp\Client([ 'verify' => false]);
        try {
            $response = $client->request('POST', "https://sso.pttati.co.id/oauth/token", [
                'headers' => ['Accept' => 'application/json'],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "client_id" => "9c88d398-15a3-4378-ac95-4725d530ba45",
                    "client_secret" =>"WKJ0dwv3th8ADqosxxZqnpLTh80RI23cFoF1vXFo",
                    "redirect_uri" => "https://loghub.pttati.co.id/callback",
                    "code" => $request->code
                ]
            ]);
            
            $respon = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $th) {
            return $th->getMessage() . '<br>' . "9c88d398-15a3-4378-ac95-4725d530ba45" . '<hr>' . var_dump($request->all());
        }

        $access_token = $respon['access_token'];
        try {
            $response = $client->request('GET', "https://sso.pttati.co.id/api/user", [
                'headers' => ['Accept' => 'application/json', "Authorization" => "Bearer " . $access_token],
                'form_params' => null
            ]);
            $userArray = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        try {
            $username = $userArray['username'];
        } catch (\Throwable $th) {
            return redirect("login")->withErrors("Gagal mendapatkan informasi login, coba lagi!");
        }

        $user = User::where("username", $username)->where('status', 'Active')->first();
        if (!$user) {
            $message = 'Akun Anda Tidak Terdaftar, Silahkan Hubungi Admin!';
            return view('auth.landing', compact('message'));
        }
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        
        Auth::login($user);
        Session::put('name', $user->name);
        Session::put('email', $user->email);
        Session::put('username', $user->username);
        Session::put('employee_id', $user->employee_id);
        Session::put('user_id', $user->user_id);
        Session::put('join_date', $user->join_date);
        Session::put('status', $user->status);
        Session::put('role_name', $user->role_name);
        Session::put('avatar', $user->avatar);

        $activityLog = [
            'name'          => $user->name,
            'username'      => $user->username,
            'employee_id'   => $user->employee_id,
            'email'         => $user->email,
            'description'   => 'Berhasil Masuk Aplikasi',
            'date_time'     => $todayDate
        ];
        DB::table('activity_logs')->insert($activityLog);

        $updateStatus = [
            'status_online' => 'Online'
        ];
        DB::table('users')->where('id', $user->id)->update($updateStatus);

        Toastr::success('Anda berhasil masuk aplikasi!', 'Success');
        return redirect()->route('showTeams');
    }
}
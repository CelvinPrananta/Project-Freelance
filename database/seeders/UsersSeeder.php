<?php

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DaftarPegawai;
use App\Models\ModeAplikasi;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::withBasicAuth('mantai', 'T@T3@M')->timeout(60)->retry(3, 1000)->get('https://management.pttati.co.id/api/list-account');
            $accounts = $response->json();

            $team = Team::create([
                'name' => 'LOG HARIAN TATI 2024',
                'description' => 'LOG HARIAN TATI 2024',
                'pattern' => 'wavy',
            ]);

            $board = Board::create([
                'team_id' => $team->id,
                'name' => 'LOG HARIAN TATI 2024',
                'pattern' => 'purple',
            ]);

            foreach ($accounts['data'] as $key => $value) {
                $user = User::create([
                    'name'                         => $value['name'],
                    'user_id'                      => 'ID_0000'.($key+1),
                    'email'                        => $value['email'],
                    'username'                     => $value['username'],
                    'employee_id'                  => str_replace(".", "", $value['id_pegawai']),
                    'join_date'                    => now()->toDayDateTimeString(),
                    'status'                       => 'Active',
                    'role_name'                    => $value['role'],
                    'avatar'                       => 'photo_defaults.jpg',
                    'password'                     => $value['password'],
                    'tema_aplikasi'                => 'Terang',
                    'status_online'                => 'Offline',
                ]);

                $pegawai = DaftarPegawai::create([
                    'name'                         => $value['name'],
                    'user_id'                      => 'ID_0000'.($key+1),
                    'email'                        => $value['email'],
                    'username'                     => $value['username'],
                    'employee_id'                  => str_replace(".", "", $value['id_pegawai']),
                    'role_name'                    => $value['role'],
                    'avatar'                       => 'photo_defaults.jpg',
                ]);

                $aplikasi = ModeAplikasi::create([
                    'name'                         => $value['name'],
                    'user_id'                      => 'ID_0000'.($key+1),
                    'email'                        => $value['email'],
                    'tema_aplikasi'                => 'Terang',
                ]);

                $userTeam = UserTeam::create([
                    'user_id' => $user->id,
                    'team_id' => $team->id,
                    'status' => $value['role'] == 'Admin' ? 'Owner' : 'Member' ,
                ]);
            }
    }
}
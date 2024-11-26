<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\DaftarPegawai;
use App\Models\ModeAplikasi;
use App\Models\Team;
use App\Models\TitleChecklists;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class SyncUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::withBasicAuth('mantai', 'T@T3@M')->timeout(60)->retry(3, 1000)->get('https://management.pttati.co.id/api/list-account');
        $accounts = $response->json();
        $team = Team::where('name', 'LOG HARIAN TATI')->first();
        $board = Board::where('team_id', $team->id)->where('name', 'LOG HARIAN TATI 2024')->first();
        foreach ($accounts['data'] as $key => $value) {
            //Cek User baru
            $cek_user = User::where('email', $value['email'])->count();
            if (empty($cek_user)) {
                $maxId = User::max('id');
                $user = User::create([
                    'name'                         => $value['name'],
                    'user_id'                      => 'ID_0000' . ($maxId + 1),
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
                    'user_id'                      => 'ID_0000' . ($maxId + 1),
                    'email'                        => $value['email'],
                    'username'                     => $value['username'],
                    'employee_id'                  => str_replace(".", "", $value['id_pegawai']),
                    'role_name'                    => $value['role'],
                    'avatar'                       => 'photo_defaults.jpg',
                ]);

                $aplikasi = ModeAplikasi::create([
                    'name'                         => $value['name'],
                    'user_id'                      => 'ID_0000' . ($maxId + 1),
                    'email'                        => $value['email'],
                    'tema_aplikasi'                => 'Terang',
                ]);

                $userTeam = UserTeam::create([
                    'user_id' => $user->id,
                    'team_id' => $team->id,
                    'status' => $value['role'] == 'Admin' ? 'Owner' : 'Member',
                ]);

                $tgl = ["9 - 13 September 2024", "16 - 20 September 2024", "23 - 27 September 2024", "30 September - 4 Oktober 2024"];

                $hari = ['Target Mingguan', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

                $column = Column::create([
                    'board_id' => $board->id,
                    'name' =>  $value['name'],
                ]);
                foreach ($tgl as $date) {
                    $card = Card::create([
                        'column_id' => $column->id,
                        'name' => $date,
                    ]);
                    foreach ($hari as $day) {
                        TitleChecklists::create([
                            'cards_id' => $card->id,
                            'name' => $day,
                        ]);
                    }
                }
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Card;
use App\Models\Team;
use App\Models\User;
use App\Models\Board;
use App\Models\Column;
use App\Models\UserTeam;
use App\Models\ModeAplikasi;
use Illuminate\Http\Request;
use App\Models\DaftarPegawai;
use App\Models\TitleChecklists;
use Illuminate\Database\QueryException;

class CreateUserController extends Controller
{
    public function create(Request $request)
    {
        if ($request->token_key == env('TOKEN_KEY')) {
            $board = Board::where('team_id', 1)->where('name', 'LOG HARIAN TATI 2024')->first();
            $cek_user = User::where('email', $request->email)->count();
            if (empty($cek_user)) {
                try {
                    $maxId = User::max('id');
                    $user_id = 'ID_0000' . ($maxId + 1);
                    User::create([
                        'name'                         => $request->name,
                        'user_id'                      => $user_id,
                        'email'                        => $request->email,
                        'username'                     => $request->username,
                        'employee_id'                  => $request->employee_id,
                        'join_date'                    => $request->join_date,
                        'status'                       => $request->status,
                        'role_name'                    => $request->role_name,
                        'avatar'                       => $request->avatar,
                        'password'                     => $request->password,
                        'tema_aplikasi'                => $request->tema_aplikasi,
                        'status_online'                => $request->status_online,
                    ]);

                    DaftarPegawai::create([
                        'name'                         => $request->name,
                        'user_id'                      => $user_id,
                        'email'                        => $request->email,
                        'username'                     => $request->username,
                        'employee_id'                  => $request->employee_id,
                        'role_name'                    => $request->role_name,
                        'avatar'                       => $request->avatar,
                    ]);
                    ModeAplikasi::create([
                        'name'                         => $request->name,
                        'user_id'                      => $user_id,
                        'email'                        => $request->email,
                        'tema_aplikasi'                => 'Terang',
                    ]);


                    $user = User::where('user_id', $user_id)->first();
                    UserTeam::create([
                        'user_id' => $user->id,
                        'team_id' => 1,
                        'status' => $request->role_name == 'Admin' ? 'Owner' : 'Member',
                    ]);


                    $tgl = ["7 - 11 Oktober 2024", "14 - 18 Oktober 2024", "21 - 25 Oktober 2024", "28 Oktober - 1 November 2024"];

                    $hari = ['Target Mingguan', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

                    $column = Column::create([
                        'board_id' => $board->id,
                        'name' => $request->name,
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

                    return response()->json([
                        'status' => 200,
                        'msg' => 'success'
                    ], 200);
                } catch (QueryException $e) {
                    $error = $e->getMessage();
                    // Extract the error message
                    if (str_contains($error, 'users_email_unique')) {
                        $msg = 'Duplicate email entry';
                    } elseif (str_contains($error, 'users_id_unique')) {
                        $msg = 'Duplicate ID entry';
                    } else {
                        $msg = 'Unknown error';
                    }
                    return response()->json([
                        'status' => 500,
                        'msg' => $msg,
                    ], 500);
                } catch (Exception $e) {
                    return response()->json([
                        'status' => 400,
                        'msg' => $e->getMessage(),
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'Email sudah terdaftar'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Token Key Invalid'
            ], 401);
        }
    }
}

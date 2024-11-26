<?php

namespace App\Http\Middleware;

use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Brian2694\Toastr\Facades\Toastr;

class BoardAccsessMiddleWare
{
    public function __construct(protected TeamLogic $teamLogic) {}
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = Auth::user()->id;
        $team_id = decrypt($request->route('team_id'));
        $board_id = $request->route('board_id');
        if (!$this->teamLogic->userHasAccsess($user_id, $team_id)) {

            Toastr::error('Tim tidak ditemukan atau Anda dikeluarkan, silakan hubungi pemiliknya.', 'Error');
            return redirect()->back();
        }

        $board = Board::find($board_id);
        if ($board == null) {
            Toastr::error('Papan tidak ditemukan atau dihapus, harap hubungi pemilik.', 'Error');
            return redirect()->back();
        }
        return $next($request);
    }
}

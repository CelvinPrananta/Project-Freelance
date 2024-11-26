<?php

namespace App\Http\Middleware;

use App\Logic\TeamLogic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Brian2694\Toastr\Facades\Toastr;

class TeamMemeberMiddlerware
{
    public function __construct(protected TeamLogic $teamLogic) {}
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = Auth::user()->id;
        $team_id = decrypt($request->route('team_ids'));
        if (!$this->teamLogic->userHasAccsess($user_id, $team_id)) {

            Toastr::error('Tim tidak ditemukan atau Anda dikeluarkan, silakan hubungi pemiliknya.', 'Error');
            return redirect()->route("showTeams");
        }
        return $next($request);
    }
}

<?php

namespace App\Logic;

use App\Models\Board;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Collection;

class TeamLogic
{
    public const PATTERN = [
        'isometric',
        'zig-zag',
        'zig-zag-flat',
        'wavy',
        'triangle',
        'triangle-2',
        'moon',
        'rect',
        'box',
        'polka',
        'polka-2',
        'paper',
        'line-bold-horizontal',
        'line-bold-vertical',
        'line-thin-diagonal',
    ];


    /**
     * get all registered teams of a given user
     *
     * @param int $user_id owner id
     * @param string $team_name team name
     * @param string $team_description team description
     * @param string $team_pattern team pattern
     *
     * @return Team created team
     */

    // Membuat Tim //
    function createTeam(int $user_id, string $team_name, string $team_description, string $team_pattern)
    {
        $newTeam = Team::create([
            "name" => $team_name,
            "description" => $team_description,
            "pattern" => $team_pattern
        ]);

        UserTeam::create([
            "user_id" => $user_id,
            "team_id" => $newTeam->id,
            "status" => "Owner"
        ]);

        return $newTeam;
    }
    // /Membuat Tim //

    /**
     * get the owner of a certain team
     *
     * @param int $team_id team id
     *
     * @return User owner of the team
     */

    // Membuat Pemilik Tim //
    function getTeamOwner(int $team_id)
    {
        $team_user_pivot = UserTeam::all()
            ->where("status", "Owner")
            ->where("team_id", $team_id)
            ->first();

        $owner = User::find($team_user_pivot->user_id);

        return $owner;
    }
    // /Membuat Pemilik Tim //

    /**
     * get the list of member from a certain team
     *
     * @param int $team_id team id
     *
     * @return Collection<int, User> list of team member
     */

    // Mendapatkan Anggota pada Tim //
    function getTeamMember(int $team_id)
    {
        $team_members = Team::find($team_id)->users()
            ->wherePivot("status", "Member")
            ->get();

        return $team_members;
    }

    function getTeamMemberPending(int $team_id)
    {
        $team_members = Team::find($team_id)->users()
            ->wherePivot("status", "Pending")
            ->get();

        return $team_members;
    }
    // /Mendapatkan Anggota pada Tim //

    /**
     * get all registered teams of a given user
     *
     * @param int $user_id user id
     *
     * @return Collection<int, Team> team where user is a member
     */

    // Mendapatkan Pengguna pada Tim //
    function getUserTeams(int $user_id, $status = ["Owner", "Member", "Pending"], $team_name = "%")
    {
        $teams = User::find($user_id)->teams()
            ->wherePivotIn("status", $status)
            ->where("name", "LIKE", "%" . $team_name . "%")
            ->get();

        return $teams;
    }
    // /Mendapatkan Pengguna pada Tim //

    /**
     * change the user team access to member
     *
     * @param int $user_id user id
     * @param int $team_id team id
     */

    // Mengundang Pengguna pada Tim //
    public function inviteAccept(int $user_id, int $team_id)
    {
        $teamStatus = UserTeam::where([
            "user_id",
            $user_id,
            "team_id",
            $team_id,
        ])->first();

        $teamStatus->status = "Member";
        return;
    }
    // /Mengundang Pengguna pada Tim //

    /**
     * get boards of a certain team
     *
     * @param int $team_id team id
     * @param string $search search string (optional)
     *
     * @return Collection<int, Board> team's boards where name contains search string
     */

    // Mendapatkan Papan //
    public function getBoards(int $team_id, string $search = "%")
    {
        $boards = Team::find($team_id)
            ->boards()
            ->where("name", "LIKE", "%" . $search . "%")
            ->get();

        return $boards;
    }
    // /Mendapatkan Papan //

    /**
     * check if user can access a sertain team
     *
     * @param int $user_id user id
     * @param int $team_id team id
     *
     * @return boolean is user has access to team
     */

    // Memberikan Akses pada Pengguna //
    public function userHasAccsess(int $user_id, int $team_id)
    {
        $isAuthorized = UserTeam::where("user_id", $user_id)
            ->where("team_id", $team_id)
            ->whereNot("status", "Pending")
            ->first();

        return !($isAuthorized == null);
    }
    // /Memberikan Akses pada Pengguna //

    /**
     * delete list of members from a given team
     * based of a given list of emails, if the
     * email is non-existent then it will be ignored
     * and continue to other emails.
     *
     * @param int $team_id team id
     * @param array $emails member emaile to be removed from team
     */

    // Menghapus Anggota //
    public function deleteMembers(int $team_id, array $emails)
    {
        $deletedUser = User::whereIn("email", $emails)->get();

        foreach ($deletedUser as $user) {
            UserTeam::where("team_id", $team_id)
                ->where("user_id", $user->id)
                ->where("status", "Member")
                ->delete();
        }

        return;
    }

    // /Menghapus Anggota //

    /**
     * delete a certain team data, including boards,
     * cards, and mebers data
     *
     * @param int $team_id team id
     */

    // Menghapus Tim //
    public function deleteTeam(int $team_id)
    {
        Board::where("team_id", $team_id)->delete();
        UserTeam::where("team_id", $team_id)->delete();
        Team::where("id", $team_id)->delete();
        return;
    }

    // /Menghapus Tim //
}

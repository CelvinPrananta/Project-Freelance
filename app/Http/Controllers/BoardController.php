<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Card;
use App\Models\Team;
use App\Models\Board;
use App\Models\Column;
use App\Logic\CardLogic;
use App\Logic\TeamLogic;
use App\Logic\BoardLogic;
use App\Models\Checklists;
use App\Models\activityLog;
use App\Models\ModeAplikasi;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\TitleChecklists;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as HttpResponse;
use PhpParser\Node\Stmt\TryCatch;

class BoardController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected BoardLogic $boardLogic,
        protected CardLogic $cardLogic
    ) {}

    public function searchCol(Request $request, $team_id, $board_id)
    {
        try {
            $search = $request->card;
            $columns = Column::where('board_id', $board_id)
                ->where('name', 'ilike', '%' . $search . '%')
                ->first();
            return response()->json([
                'name' => $columns->name,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'info',
                'title' => 'List Tidak Ditemukan!',
                'msg' => 'Tidak ada hasil pencarian yang cocok.'
            ], 404);
        }
    }
    // Membuat Papan Khusus Admin //
    public function createBoard(Request $request, $team_id)
    {
        $request->validate([
            "team_id" => "required",
            "board_name" => "required",
            "board_pattern" => "required"
        ]);
        $team_id = intval($request->team_id);

        $createdBoard = $this->boardLogic->createBoard(
            $team_id,
            $request->board_name,
            $request->board_pattern,
        );

        if ($createdBoard == null)
            Toastr::error('Gagal membuat papan, silahkan coba lagi!', 'Error');
        return redirect()->back();

        Toastr::success('Papan berhasil dibuat!', 'Success');
        return redirect()->back();
    }
    // /Membuat Papan Khusus Admin //

    // Tampilan Papan Admin //
    public function showBoard($team_id, $board_id)
    {
        // dd('ahi');
        $team_id = decrypt($team_id);
        // $board_id = decrypt($board_id);
        $userID = Auth::id();
        $board = $this->boardLogic->getData($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($board->team_id);
        $dataColumnCard = Column::with(['cards.titleChecklists.checklists'])
            ->where('board_id', $board_id)
            ->get();
        $UserTeams = DB::table('users')->select('name', 'email', 'username', 'avatar')->get();
        $actionTeams = DB::table('user_team')->where('team_id', '=', $team_id)->where('status', '=', 'Owner')->where('user_id', '=', $userID)->get();
        return view("LogHub.board")
            ->with("UserTeams", $UserTeams)
            ->with("actionTeams", $actionTeams)
            ->with("dataColumnCard", $dataColumnCard)
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("team_id", $team_id)
            ->with("board_id", $board_id)
            ->with("patterns", BoardLogic::PATTERN)
            ->with("covers", BoardLogic::COVER);
    }
    // /Tampilan Papan Admin //

    // Ambil Data Modal Kartu Admin //
    public function getDataKartu(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $isianKartu = Card::with(['titleChecklists.checklists'])->find($request->card_id);
        $dataKolom = Column::find($isianKartu->column_id);
        $UserTeams = DB::table('users')->select('name', 'email', 'username', 'avatar')->get();
        return view('LogHub.isi-kartu')
            ->with("isianKartu", $isianKartu)
            ->with("dataKolom", $dataKolom)
            ->with("UserTeams", $UserTeams)
            ->with("patterns", BoardLogic::PATTERN)
            ->with("covers", BoardLogic::COVER);
    }
    // /Ambil Data Modal Kartu Admin //

    // Perbaharui Papan Khusus Admin //
    public function updateBoard(Request $request)
    {
        $request->validate([
            "board_id"      => "required",
            "board_name"    => "required",
            "board_pattern" => "required",
        ]);

        $board = Board::find(intval($request->board_id));
        $board->name = $request->board_name;
        $board->pattern = $request->board_pattern;
        $board->save();

        Toastr::success('Papan berhasil diperbaharui!', 'Success');
        return redirect()->back();
    }
    // /Perbaharui Papan Khusus Admin //

    // Hapus Papan Khusus Admin //
    public function deleteBoard($team_id, $board_id)
    {
        $team_id = decrypt($team_id);
        // $board_id = decrypt($board_id);
        Board::where("id", intval($board_id))->delete();
        Toastr::success('Papan berhasil dihapus!', 'Success');
        return redirect()->route("viewTeam", ["team_ids" => encrypt($team_id)]);
    }
    // /Hapus Papan Khusus Admin //

    // Membuat Kolom Admin //
    public function addColumn(Request $request, $team_id, $board_id)
    {
        $team_id = decrypt($team_id);
        // $board_id = decrypt($board_id);
        $request->validate([
            "board_id" => "required",
            "column_name" => "required",
        ]);

        $team_id = intval($team_id);
        $board_id = intval($request->board_id);

        $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

        if ($createdColumn == null) {
            return response()->json(['error' => 'Gagal membuat kolom, silahkan coba lagi!'], 500);
        }

        $softDeletedCards = Card::where('column_id', $createdColumn->id)->onlyTrashed()->count();

        return response()->json([
            'id' => $createdColumn->id,
            'name' => $createdColumn->name,
            'updateUrl' => route('updateCol', ['board_id' => $board_id, 'team_id' => encrypt($team_id)]),
            'deleteUrl' => route('deleteCol', ['board_id' => $board_id, 'team_id' => encrypt($team_id)]),
            'addCardUrl' => route('addCard', ['board_id' => $board_id, 'team_id' => encrypt($team_id), 'column_id' => $createdColumn->id]),
            'board_id' => $board_id,
            'team_id' => $team_id,
            'softDeletedCards' => $softDeletedCards,
        ]);
    }
    // /Membuat Kolom Admin //

    // Perbaharui Kolom Admin //
    public function updateCol(Request $request, $team_id, $board_id)
    {
        $team_id = decrypt($team_id);
        // $board_id = decrypt($board_id);
        $request->validate([
            "column_name" => "required|max:200",
            "column_id" => "required",
        ]);

        $col_id = intval($request->column_id);
        $column = Column::find($col_id);
        if (!$column) {
            return response()->json(['error' => 'Kolom tidak ditemukan atau terhapus harap menghubungi pemiliknya'], 404);
        }
        $column->name = $request->column_name;
        $column->save();
        return response()->json(['name' => $column->name], 200);
    }
    // /Perbaharui Kolom Admin //

    // Menghapus Kolom Admin //
    public function deleteCol(Request $request, $team_id, $board_id)
    {
        $request->validate(["column_id" => "required"]);
        $col_id = intval($request->column_id);
        $this->boardLogic->deleteCol($col_id);

        $columnId = $request->column_id;
        $softDeletedColumns = Column::onlyTrashed()->count();

        return response()->json([
            'message' => 'Berhasil menghapus kolom!',
            'columnId' => $columnId,
            'softDeletedColumns' => $softDeletedColumns
        ]);
    }
    // /Menghapus Kolom Admin //

    // Membuat Kartu Admin //
    public function addCard(Request $request, $team_id, $board_id, $column_id)
    {
        // Mendapatkan data board->id dan column->id
        $team_id = decrypt($team_id);
        // $board_id = decrypt($board_id);
        $column_id = intval($column_id);

        // Dapatkan nama kartu dari permintaan
        $card_name = $request->name;

        // Tambahkan kartu baru menggunakan boardLogic
        $newCard = $this->boardLogic->addCard($column_id, $card_name);

        // Membuat histori pembuatan kartu
        $this->cardLogic->cardAddEvent($newCard->id, Auth::user()->id, "Membuat Kartu");

        // Temukan column->id tempat kartu ditambahkan
        $newColumn = Column::where('id', $column_id)->first();

        // Periksa apakah newCard dan newColumn berhasil dibuat dan diambil
        if ($newCard && $newColumn) {
            return response()->json([
                'success' => true,

                'column' => [
                    'id' => $newColumn->id,
                    'name' => $newColumn->name,
                ],

                'card' => [
                    'id' => $newCard->id,
                    'name' => $newCard->name,
                    'pattern' => $newCard->pattern,
                    'description' => $newCard->description,
                    'updateUrl' => route('perbaharuiKartu', ['card_id' => $newCard->id]),
                    'deleteUrl' => route('hapusKartu', ['card_id' => $newCard->id]),
                    'copyCardUrl' => route('copyCard', ['column_id' => $newColumn->id, 'id' => $newCard->id])
                ]
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    // /Membuat Kartu Admin //

    // Perbaharui Kartu Admin //
    public function perbaharuiKartu(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                "name" => "required|max:200"
            ]);

            $user_id = Auth::user()->id;

            $card_id = intval($card_id);
            $card = Card::find($card_id);

            $updateKartu = [
                'id'            => $request->id,
                'name'          => $request->name,
            ];

            $card->update($updateKartu);
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Kartu");

            DB::commit();
            return response()->json(['name' => $request->name], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memperbaharui kartu!'], 500);
        }
    }
    // Perbaharui Kartu Admin //

    // Perbaharui Kartu Admin //
    public function hapusKartu(Request $request, $card_id)
    {
        DB::beginTransaction();
        try {
            $card_id = intval($card_id);
            $card = Card::findOrFail($card_id);

            // Asumsi kartu memiliki bidang column->id
            $columnId = $card->column_id;
            $card->delete();

            TitleChecklists::where('cards_id', $card_id)->get()->each(function ($titleChecklist) {
                $titleChecklist->delete();

                Checklists::where('title_checklists_id', $titleChecklist->id)->get()->each(function ($checklist) {
                    $checklist->delete();
                });
            });

            $user_id = Auth::user()->id;
            $card_id = intval($card_id);
            $card = Card::find($card_id);
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Kartu");

            $softDeletedCards = Card::where('column_id', $columnId)->onlyTrashed()->count();

            // Untuk icon checklist
            $titleChecklistID = TitleChecklists::where('cards_id', $card_id)->pluck('id');
            $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
            $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

            // Hitung persentase
            $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;
            DB::commit();
            return response()->json([
                'message' => 'Berhasil menghapus kartu!',
                'softDeletedCards' => $softDeletedCards,
                'columnId' => $columnId,
                'perChecklist' => $perChecklist,
                'jumlahChecklist' => $jumlahChecklist,
                'totalPercentage' => $totalPercentage,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menghapus kartu!']);
        }
    }
    // Perbaharui Kartu Admin //

    // Fitur Pemulihan Data Kartu //
    public function pulihkanKartu(Request $request)
    {
        $card = Card::withTrashed()->find($request->id);

        if ($card) {

            // Asumsi kartu memiliki bidang column->id
            $columnId = $card->column_id;

            // Pulihkan Kartu
            $card->restore();

            // Pulihkan Judul Checklist
            TitleChecklists::withTrashed()->where('cards_id', $card->id)->get()->each(function ($titleChecklist) {

                // Pulihkan Judul Checklist
                $titleChecklist->restore();

                // Pulihkan Checklist
                Checklists::withTrashed()->where('title_checklists_id', $titleChecklist->id)->restore();
            });

            $user_id = Auth::user()->id;
            $this->cardLogic->cardAddEvent($card->id, $user_id, "Memulihkan Kartu");

            $softDeletedCards = Card::where('column_id', $columnId)->onlyTrashed()->count();

            // Untuk icon checklist
            $titleChecklistID = TitleChecklists::where('cards_id', $card->id)->pluck('id');
            $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
            $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

            // Hitung persentase
            $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

            // Mendapatkan tema aplikasi
            $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
            $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
            $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

            return response()->json([
                'message' => 'Berhasil memulihkan kartu!',
                'softDeletedCards' => $softDeletedCards,
                'columnId' => $columnId,
                'new_card' => $card,

                'column' => [
                    'id' => $columnId,
                    'name' => $card->column->name,
                ],

                'card' => [
                    'id' => $card->id,
                    'name' => $card->name,
                    'pattern' => $card->pattern,
                    'description' => $card->description,
                    'position' => $card->position,
                    'updateUrl' => route('perbaharuiKartu', ['card_id' => $card->id]),
                    'deleteUrl' => route('hapusKartu', ['card_id' => $card->id]),
                    'copyCardUrl' => route('copyCard', ['column_id' => $columnId, 'id' => $card->id])
                ],

                'perChecklist' => $perChecklist,
                'jumlahChecklist' => $jumlahChecklist,
                'totalPercentage' => $totalPercentage,
                'result_tema' => [
                    'tema_aplikasi' => $tema_aplikasi
                ]
            ]);
        }
    }

    public function hapusKartuPermanen(Request $request)
    {
        $card = Card::withTrashed()->find($request->id);

        if ($card) {

            // Hapus Judul Checklist
            TitleChecklists::withTrashed()->where('cards_id', $card->id)->get()->each(function ($titleChecklist) {

                // Hapus Checklist
                Checklists::withTrashed()->where('title_checklists_id', $titleChecklist->id)->forceDelete();

                // Hapus Judul Checklist
                $titleChecklist->forceDelete();
            });

            // Hapus Kartu
            $card->forceDelete();

            $columnId = $card->column_id;
            $softDeletedCards = Card::where('column_id', $columnId)->onlyTrashed()->count();

            return response()->json([
                'message' => 'Berhasil menghapus kartu permanen!',
                'softDeletedCards' => $softDeletedCards,
                'columnId' => $columnId,
            ]);
        } else {
            return response()->json(['message' => 'Kartu tidak ditemukan!'], 404);
        }
    }

    public function dataPulihkanKartu(Request $request)
    {
        $dataRecover = Card::onlyTrashed()->where('column_id', $request->column_id)->get();

        return response()->json(['dataRecover' => $dataRecover]);
    }
    // /Fitur Pemulihan Data Kartu //

    // Perbaharui Deskripsi Kartu Admin //
    public function addDescription(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'description' => $request->keterangan
        ]);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $status_keterangan = empty($request->keterangan) ? 'hide' : 'show';
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Keterangan Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!', 'status_keterangan' => $status_keterangan]);
    }
    // Perbaharui Deskripsi Kartu Admin //

    // Perbaharui Cover Kartu Admin //
    public function perbaharuiCover(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'pattern' => $request->pattern
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Cover Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // /Perbaharui Cover Kartu Admin //

    // Hapus Cover Kartu Admin //
    public function hapusCover(Request $request)
    {
        Card::where('id', $request->card_id)->update([
            'pattern' => NULL
        ]);

        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Cover Kartu");

        return response()->json(['message' => 'Data berhasil disimpan!']);
    }
    // /Hapus Cover Kartu Admin //


    // Menambahkan Komen Admin //
    public function komentarKartu(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    "content" => "required|max:255"
                ]
            );

            $user_id = $request->user_id;
            $card_id = $request->card_id;

            $createComment = [
                "user_id"   => $user_id,
                "card_id"   => $card_id,
                "type"      => "comment",
                "content"   => $request->content,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            DB::table('card_histories')->insert($createComment);

            DB::commit();
            $response = [
                'name' => Auth::user()->name,
                'content' => $request->content,
                'created_at' => Carbon::now()->translatedFormat('j F \p\u\k\u\l h:i A')
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memberikan komentar!'], 500);
        }
    }
    // /Menambahkan Komen Admin //


    // Untuk Pindah Posisi Kolom //
    public function perbaharuiPosisiKolom(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $position) {
            Column::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Kolom //

    // Untuk Pindah Posisi Kartu //
    public function perbaharuiPosisiKartu(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $position) {
            Card::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Kartu //

    // Untuk Pindah Posisi Kartu Ke Dalam Kolom //
    public function perbaharuiPosisiKartuKeKolom(Request $request)
    {
        $cardId = $request->input('card_id');
        $newColumnId = $request->input('new_column_id');

        // Update the card's column_id
        Card::where('id', $cardId)->update(['column_id' => $newColumnId]);

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Kartu Ke Dalam Kolom //

    // Untuk Pindah Posisi Judul //
    public function perbaharuiPosisiJudul(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $position) {
            TitleChecklists::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
    // /Untuk Pindah Posisi Judul //

    // Untuk Pindah Posisi Checklist //
    public function perbaharuiPosisiCeklist(Request $request)
    {
        $positions = $request->input('positions');

        foreach ($positions as $id => $data) {
            Checklists::where('id', $id)->update([
                'position' => $data['position'],
                'title_checklists_id' => $data['title_id'],
            ]);
        }

        $titleChecklist = TitleChecklists::find($data['title_id']);
        $getTitleChecklist = TitleChecklists::where('cards_id', $titleChecklist->cards_id)->get();

        $titleChecklistsWithProgress = [];
        foreach ($getTitleChecklist as $id2 => $data2) {
            // Perbaharui Progress Bar
            $progressData = $this->progressBar($data2->id);
            $titleChecklistsWithProgress[] = $progressData;
        }

        return response()->json([
            'success' => true,
            'titlechecklist' => $titleChecklistsWithProgress,
        ]);
    }

    // public static function progressBar($title_checklists_id)
    // {
    //     $totData = Checklists::where('title_checklists_id', $title_checklists_id)->count();
    //     $countActive = Checklists::where('title_checklists_id', $title_checklists_id)->where('is_active', 1)->count();
    //     $percentage = !empty($countActive) ? round(($countActive / $totData) * 100) : 0;
    //     TitleChecklists::where('id', $title_checklists_id)->update([
    //         'percentage' => $percentage
    //     ]);
    //     $titleChecklist = TitleChecklists::find($title_checklists_id);

    //     return $titleChecklist;
    // }
    // /Untuk Pindah Posisi Checklist //

    // Memindahkan Kartu Admin & User //
    public function reorderCard(Request $request, $team_id, $board_id)
    {
        $board_id = intval($board_id);
        $column_id = intval($request->column_id);
        $middle_id = intval($request->middle_id);
        $bottom_id = intval($request->bottom_id);
        $top_id = intval($request->top_id);

        $updatedCard = $this->boardLogic->moveCard($middle_id, $column_id, $bottom_id, $top_id);

        return response()->json($updatedCard);
    }
    // /Memindahkan Kartu Admin & User //

    // Memindahkan Kolom Admin //
    public function reorderCol(Request $request, $team_id, $board_id)
    {
        $user_id = Auth::user()->id;
        $board_id = intval($board_id);
        $middle_id = intval($request->middle_id);
        $right_id = intval($request->right_id);
        $left_id = intval($request->left_id);

        if (!$this->boardLogic->hasAccess($user_id, $board_id)) {
            return response()->json(["url" => route("showTeams")], HttpResponse::HTTP_BAD_REQUEST);
        }

        $updatedCol = $this->boardLogic->moveCol($middle_id, $right_id, $left_id);
        return response()->json($updatedCol);
    }
    // /Memindahkan Kolom Admin //

    public static function progressBar($title_checklists_id)
    {
        $totData = Checklists::where('title_checklists_id', $title_checklists_id)->count();
        $countActive = Checklists::where('title_checklists_id', $title_checklists_id)->where('is_active', 1)->count();
        $percentage = !empty($countActive) ? round(($countActive / $totData) * 100) : 0;
        TitleChecklists::where('id', $title_checklists_id)->update([
            'percentage' => $percentage
        ]);
        $titleChecklist = TitleChecklists::find($title_checklists_id);

        return $titleChecklist;
    }
}

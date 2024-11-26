<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TitleChecklists;
use App\Models\Checklists;
use App\Models\ModeAplikasi;
use Illuminate\Support\Facades\Auth;
use App\Logic\CardLogic;

class ChecklistController extends Controller
{
    public function __construct(
        protected CardLogic $cardLogic
    ) {}

    // Tambahkan Title Kartu Admin //
    public function addTitle(Request $request)
    {
        $titlechecklist = TitleChecklists::create([
            'cards_id' => $request->card_id,
            'name' => $request->titleChecklist
        ]);
        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Judul Checklist");

        $getChecklistPercentage = TitleChecklists::where('id', $titlechecklist->id)->first()->percentage ?? 0;
        $titleChecklistsPercentage = $getChecklistPercentage === 100 ? 'checked' : '';

        // Untuk icon checklist
        $titleChecklistID = TitleChecklists::where('cards_id', $card_id)->pluck('id');
        $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
        $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

        // Hitung persentase
        $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'card_id' => $request->card_id,
            'titlechecklist' => $titlechecklist,
            'titleChecklistsPercentage' => $titleChecklistsPercentage,
            'jumlahChecklist' => $jumlahChecklist,
            'perChecklist' => $perChecklist,
            'percentage' =>  $getChecklistPercentage,
        ]);
    }
    // /Tambahkan Title Kartu Admin //

    // Untuk membuat template judul checklist
    public function templateTitle(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'cards_id' => 'required|exists:cards,id',
            'name' => 'required|array',
            'name.*' => 'required|string',
        ]);

        // Array untuk menyimpan judul checklist yang dibuat
        $titleChecklists = [];

        // Ulangi setiap nama dan simpan ke database
        foreach ($validatedData['name'] as $name) {
            $titleChecklist = new TitleChecklists();
            $titleChecklist->cards_id = $validatedData['cards_id'];
            $titleChecklist->name = $name;
            $titleChecklist->percentage = 0;
            $titleChecklist->position = 0;
            $titleChecklist->save();

            // Simpan setiap judul checklist yang dibuat ke dalam array
            $titleChecklists[] = $titleChecklist;
        }

        // Tambahkan acara ke riwayat kartu
        $userId = Auth::user()->id;
        $cardId = $request->cards_id;
        $this->cardLogic->cardAddEvent($cardId, $userId, "Membuat Judul Checklist");

        // Untuk icon checklist
        $titleChecklistID = TitleChecklists::where('cards_id', $cardId)->pluck('id');
        $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
        $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

        // Hitung persentase
        $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

        return response()->json([
            'message' => 'Template judul checklist berhasil dibuat!',
            'card_id' => $request->cards_id,
            'titlechecklists' => $titleChecklists,
            'jumlahChecklist' => $jumlahChecklist,
            'perChecklist' => $perChecklist,
            'percentage' => $totalPercentage,
        ]);
    }
    // /Untuk membuat template judul checklist

    // Untuk membuat centang semua checklist
    public function perbaharuiSemuaChecklist(Request $request)
    {
        $titleChecklistsId = $request->title_checklists_id;
        $isActive = $request->is_active;

        // Perbarui semua checklist yang terkait dengan judul ini
        Checklists::where('title_checklists_id', $titleChecklistsId)
            ->update(['is_active' => $isActive]);

        // Hitung persentase checklist yang sudah dicentang
        $totalChecklists = Checklists::where('title_checklists_id', $titleChecklistsId)->count();
        $completedChecklists = Checklists::where('title_checklists_id', $titleChecklistsId)
            ->where('is_active', 1)
            ->count();

        $percentage = ($totalChecklists > 0) ? ($completedChecklists / $totalChecklists) * 100 : 0;

        // Perbarui persentase di tabel title_checklists
        TitleChecklists::where('id', $titleChecklistsId)
            ->update(['percentage' => $percentage]);

        // Mendapatkan data checklist
        $checklists = Checklists::where('title_checklists_id', $titleChecklistsId)->get();

        // Mendapatkan data progress bar
        $titleChecklist = $this->progressBar($titleChecklistsId);

        // Untuk icon checklist
        $cardId = $titleChecklist->cards_id;
        $titleChecklistID = TitleChecklists::where('cards_id', $cardId)->pluck('id');
        $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
        $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

        // Hitung persentase
        $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

        // Mendapatkan tema aplikasi
        $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
        $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
        $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

        return response()->json([
            'message' => 'Berhasil centang semua checklist!',
            'checklist' => $checklists,
            'titlechecklist' => $titleChecklist,
            'jumlahChecklist' => $jumlahChecklist,
            'perChecklist' => $perChecklist,
            'totalPercentage' => $totalPercentage,
            'result_tema' => [
                'tema_aplikasi' => $tema_aplikasi
            ]
        ]);
    }
    // /Untuk membuat centang semua checklist

    // Perbaharui Title Kartu Admin //
    public function updateTitle(Request $request)
    {
        $user_id = AUth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Judul Checklist");

        $titlechecklist = TitleChecklists::where('id', $request->title_id)->update([
            'name' => $request->titleChecklistUpdate
        ]);

        return response()->json([
            'name' => $request->titleChecklistUpdate,
            'message' => 'Data berhasil disimpan!',
            'card_id' => $request->card_id,
            'titlechecklist' => $titlechecklist
        ]);
    }
    // /Perbaharui Title Kartu Admin //

    // Hapus Judul Kartu Admin //
    public function hapusTitle(Request $request)
    {
        DB::beginTransaction();
        try {
            $titleChecklist = TitleChecklists::findOrFail($request->id);

            // Asumsi title checklist memiliki bidang cards->id
            $cardId = $titleChecklist->cards_id;

            // Hapus checklist yang terkait terlebih dahulu
            $Checklist = Checklists::where('title_checklists_id', $titleChecklist->id)->delete();

            // Hapus title checklist
            $titleChecklist->delete();

            $user_id = Auth::user()->id;
            $this->cardLogic->cardAddEvent($cardId, $user_id, "Menghapus Judul Checklist");

            // Hitung berapa banyak judul checklist yang masih dihapus untuk kartu ini
            $softDeletedTitle = TitleChecklists::where('cards_id', $cardId)->onlyTrashed()->count();

            // Hitung berapa banyak checklist yang masih dihapus untuk semua title checklists di kartu ini
            $softDeletedChecklist = Checklists::whereIn('title_checklists_id', function ($query) use ($cardId) {
                $query->select('id')->from('title_checklists')->where('cards_id', $cardId);
            })->onlyTrashed()->count();

            // Untuk icon checklist
            $titleChecklistID = TitleChecklists::where('cards_id', $cardId)->pluck('id');
            $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
            $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

            // Hitung persentase
            $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

            // Mendapatkan tema aplikasi
            $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
            $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
            $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

            DB::commit();
            return response()->json([
                'message' => 'Berhasil menghapus judul checklist!',
                'softDeletedTitle' => $softDeletedTitle,
                'softDeletedChecklist' => $softDeletedChecklist,
                'cardId' => $cardId,
                'titlechecklist' => $titleChecklist,
                'checklist' => $Checklist,
                'jumlahChecklist' => $jumlahChecklist,
                'perChecklist' => $perChecklist,
                'totalPercentage' => $totalPercentage,
                'result_tema' => [
                    'tema_aplikasi' => $tema_aplikasi
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menghapus judul checklist!']);
        }
    }
    // /Hapus Judul Kartu Admin //

    // Tambahkan Checklist Admin //
    public function addChecklist(Request $request)
    {
        $data = Checklists::create([
            'title_checklists_id' => $request->title_id,
            'name' => $request->checklist
        ]);

        //Get Checklists
        $checklist = Checklists::where('title_checklists_id', $request->title_id)->where('id', $data->id)->first();

        //Get Progress Bar
        $titleChecklist = $this->progressBar($checklist->title_checklists_id);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Membuat Checklist");

        // Untuk icon checklist
        $titleChecklistID = TitleChecklists::where('cards_id', $card_id)->pluck('id');
        $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
        $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

        // Hitung persentase
        $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

        // Mendapatkan tema aplikasi
        $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
        $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
        $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

        return response()->json([
            'message' => 'Data berhasil ditambahkan!',
            'checklist' => $checklist,
            'titlechecklist' => $titleChecklist,
            'perChecklist' => $perChecklist,
            'jumlahChecklist' => $jumlahChecklist,
            'totalPercentage' => $totalPercentage,
            'result_tema' => [
                'tema_aplikasi' => $tema_aplikasi
            ]
        ]);
    }
    // /Tambahkan Checklist Admin //

    // Perbaharui Checklist Admin //
    public function updateChecklist(Request $request)
    {
        $is_active = $request->{$request->checklist_id} == 'on' ? 1 : 0;

        //Update Checklists
        Checklists::where('id', $request->checklist_id)->update([
            'name' => $request->{'checkbox-' . $request->checklist_id},
            'is_active' => $is_active,
        ]);

        //Get Checklists
        $checklist = Checklists::find($request->checklist_id);

        //Get Progress Bar
        $titleChecklist = $this->progressBar($checklist->title_checklists_id);

        $user_id = Auth::user()->id;
        $card_id = $request->card_id;
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Memperbaharui Checklist");

        // Untuk icon checklist
        $titleChecklistID = TitleChecklists::where('cards_id', $card_id)->pluck('id');
        $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
        $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

        // Hitung persentase
        $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

        // Mendapatkan tema aplikasi
        $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
        $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
        $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

        return response()->json([
            'message' => 'Data berhasil diperbaharui!',
            'checklist' => $checklist,
            'titlechecklist' => $titleChecklist,
            'perChecklist' => $perChecklist,
            'jumlahChecklist' => $jumlahChecklist,
            'totalPercentage' => $totalPercentage,
            'result_tema' => [
                'tema_aplikasi' => $tema_aplikasi
            ]
        ]);
    }
    // /Perbaharui Checklist Admin //

    // Hapus Checklist Kartu Admin //
    public function hapusChecklist(Request $request)
    {
        DB::beginTransaction();
        try {
            $checklist = Checklists::findOrFail($request->id);

            // Asumsi checklist memiliki bidang title_checklists_id
            $titleChecklistId = $checklist->title_checklists_id;

            // Hapus checklist
            $checklist->delete();

            // Perbarui persentase untuk checklist
            $titleChecklist = $this->progressBar($request->title_checklists_id);

            // Ambil card_id dari titleChecklists terkait
            $dataCardID = TitleChecklists::findOrFail($titleChecklistId);
            $cardId = $dataCardID->cards_id;

            $user_id = Auth::user()->id;
            $card_id = $request->card_id;
            $this->cardLogic->cardAddEvent($card_id, $user_id, "Menghapus Checklist");

            // Hitung berapa banyak judul checklist yang masih dihapus untuk kartu ini
            $softDeletedTitle = TitleChecklists::where('cards_id', $cardId)->onlyTrashed()->count();

            // Hitung berapa banyak checklist yang masih dihapus untuk semua title checklists di kartu ini
            $softDeletedChecklist = Checklists::whereIn('title_checklists_id', function ($query) use ($cardId) {
                $query->select('id')->from('title_checklists')->where('cards_id', $cardId);
            })->onlyTrashed()->count();

            // Untuk icon checklist
            $titleChecklistID = TitleChecklists::where('cards_id', $card_id)->pluck('id');
            $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
            $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

            // Hitung persentase
            $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

            // Mendapatkan tema aplikasi
            $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
            $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
            $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

            DB::commit();
            return response()->json([
                'message' => 'Berhasil menghapus checklist!',
                'softDeletedTitle' => $softDeletedTitle,
                'softDeletedChecklist' => $softDeletedChecklist,
                'cardId' => $cardId,
                'titleChecklistId' => $titleChecklistId,
                'titlechecklist' => $titleChecklist,
                'checklist' => $checklist,
                'perChecklist' => $perChecklist,
                'jumlahChecklist' => $jumlahChecklist,
                'totalPercentage' => $totalPercentage,
                'result_tema' => [
                    'tema_aplikasi' => $tema_aplikasi
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal menghapus checklist!']);
        }
    }
    // /Hapus Checklist Kartu Admin //

    // Fitur Pemulihan Data Judul Checklist //
    public function pulihkanJudulChecklist(Request $request)
    {
        DB::beginTransaction();
        try {
            // Temukan Judul Checklist yang dihapus
            $titleChecklist = TitleChecklists::withTrashed()->findOrFail($request->id);

            // Pulihkan Judul Checklist
            $titleChecklist->restore();

            // Pulihkan semua Checklist terkait dengan Judul Checklist ini
            $checklistsQuery = Checklists::withTrashed()->where('title_checklists_id', $titleChecklist->id);
            $checklistsQuery->restore();

            // Ambil semua checklist yang terkait setelah pemulihan
            $checklistsData = $checklistsQuery->get();

            // Asumsi Judul Checklist memiliki bidang cards->id
            $cardId = $titleChecklist->cards_id;

            // Catat event pemulihan
            $user_id = Auth::user()->id;
            $this->cardLogic->cardAddEvent($cardId, $user_id, "Memulihkan Judul Checklist");

            // Hitung berapa banyak judul checklist yang masih dihapus untuk kartu ini
            $softDeletedTitle = TitleChecklists::where('cards_id', $cardId)->onlyTrashed()->count();

            // Hitung berapa banyak checklist yang masih dihapus untuk semua title checklists di kartu ini
            $softDeletedChecklist = Checklists::whereIn('title_checklists_id', function ($query) use ($cardId) {
                $query->select('id')->from('title_checklists')->where('cards_id', $cardId);
            })->onlyTrashed()->count();

            $getChecklistPercentage = TitleChecklists::where('id', $titleChecklist->id)->first()->percentage ?? 0;
            $titleChecklistsPercentage = $getChecklistPercentage === 100 ? 'checked' : '';

            // Untuk icon checklist
            $titleChecklistID = TitleChecklists::where('cards_id', $cardId)->pluck('id');
            $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
            $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

            // Hitung persentase
            $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

            // Mendapatkan tema aplikasi
            $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
            $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
            $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

            DB::commit();
            return response()->json([
                'message' => 'Berhasil memulihkan judul checklist!',
                'softDeletedTitle' => $softDeletedTitle,
                'softDeletedChecklist' => $softDeletedChecklist,
                'cardId' => $cardId,
                'titlechecklist' => $titleChecklist,
                'checklists' => $checklistsData,
                'titleChecklistsPercentage' => $titleChecklistsPercentage,
                'perChecklist' => $perChecklist,
                'jumlahChecklist' => $jumlahChecklist,
                'totalPercentage' => $totalPercentage,
                'result_tema' => [
                    'tema_aplikasi' => $tema_aplikasi
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal memulihkan judul checklist!']);
        }
    }
    // /Fitur Pemulihan Data Judul Checklist //

    // Fitur Hapus Judul Checklist Permanen //
    public function hapusJudulChecklistPermanen(Request $request)
    {
        $titleChecklist = TitleChecklists::withTrashed()->find($request->id);

        if ($titleChecklist) {
            // Hapus Checklist
            $checklistsQuery = Checklists::withTrashed()->where('title_checklists_id', $titleChecklist->id);
            $checklistsQuery->forceDelete();
            $checklistsData = $checklistsQuery->get();

            // Hapus Kartu
            $titleChecklist->forceDelete();

            $cardId = $titleChecklist->cards_id;

            // Hitung berapa banyak judul checklist yang masih dihapus untuk kartu ini
            $softDeletedTitle = TitleChecklists::where('cards_id', $cardId)->onlyTrashed()->count();

            // Hitung berapa banyak checklist yang masih dihapus untuk semua title checklists di kartu ini
            $softDeletedChecklist = Checklists::whereIn('title_checklists_id', function ($query) use ($cardId) {
                $query->select('id')->from('title_checklists')->where('cards_id', $cardId);
            })->onlyTrashed()->count();

            return response()->json([
                'message' => 'Berhasil menghapus judul checklist permanen!',
                'softDeletedTitle' => $softDeletedTitle,
                'softDeletedChecklist' => $softDeletedChecklist,
                'cardId' => $cardId,
                'titlechecklist' => $titleChecklist,
                'checklists' => $checklistsData
            ]);
        } else {
            return response()->json(['message' => 'Kartu tidak ditemukan!'], 404);
        }
    }
    // /Fitur Hapus Judul Checklist Permanen //

    // Fitur Pemulihan Data Checklist //
    public function pulihkanChecklist(Request $request)
    {
        DB::beginTransaction();
        try {
            // Temukan Checklist yang dihapus
            $checklist = Checklists::withTrashed()->findOrFail($request->id);

            // Asumsi checklist memiliki bidang title_checklists_id
            $titleChecklistId = $checklist->title_checklists_id;

            // Ambil data title checklist sebelum restore
            $titleChecklistData = TitleChecklists::withTrashed()->findOrFail($titleChecklistId);

            // Pulihkan Checklist
            $checklist->restore();

            // Asumsi Checklist memiliki bidang cards->id
            $cardId = $titleChecklistData->cards_id;

            // Perbarui persentase untuk checklist
            $titleChecklist = $this->progressBar($titleChecklistId);

            // Catat event pemulihan
            $user_id = Auth::user()->id;
            $this->cardLogic->cardAddEvent($cardId, $user_id, "Memulihkan Checklist");

            // Hitung berapa banyak judul checklist yang masih dihapus untuk kartu ini
            $softDeletedTitle = TitleChecklists::where('cards_id', $cardId)->onlyTrashed()->count();

            // Hitung berapa banyak checklist yang masih dihapus untuk semua title checklists di kartu ini
            $softDeletedChecklist = Checklists::whereIn('title_checklists_id', function ($query) use ($cardId) {
                $query->select('id')->from('title_checklists')->where('cards_id', $cardId);
            })->onlyTrashed()->count();

            // Untuk icon checklist
            $titleChecklistID = TitleChecklists::where('cards_id', $cardId)->pluck('id');
            $perChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count();
            $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

            // Hitung persentase
            $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

            // Mendapatkan tema aplikasi
            $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
            $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
            $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

            DB::commit();
            return response()->json([
                'message' => 'Berhasil memulihkan checklist!',
                'softDeletedTitle' => $softDeletedTitle,
                'softDeletedChecklist' => $softDeletedChecklist,
                'cardId' => $cardId,
                'titlechecklist' => $titleChecklist,
                'checklist' => $checklist,
                'perChecklist' => $perChecklist,
                'jumlahChecklist' => $jumlahChecklist,
                'totalPercentage' => $totalPercentage,
                'result_tema' => [
                    'tema_aplikasi' => $tema_aplikasi
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal memulihkan checklist!']);
        }
    }
    // Fitur Pemulihan Data Checklist //

    // Fitur Hapus Checklist Permanen //
    public function hapusChecklistPermanen(Request $request)
    {
        // Mencari data checklist
        $checklist = Checklists::withTrashed()->where('id', $request->id)->first();

        if ($checklist) {
            // Mengambil title_checklists yang terkait
            $titleChecklist = TitleChecklists::withTrashed()->find($checklist->title_checklists_id);

            if ($titleChecklist) {
                // Menghapus checklist secara permanen
                $checklist->forceDelete();

                // Mendapatkan semua data checklist setelah penghapusan
                $checklistsData = Checklists::withTrashed()->where('title_checklists_id', $checklist->title_checklists_id)->get();

                // Asumsi cardId memiliki bidang cards_id
                $cardId = $titleChecklist->cards_id;

                // Hitung berapa banyak judul checklist yang masih dihapus untuk kartu ini
                $softDeletedTitle = TitleChecklists::where('cards_id', $cardId)->onlyTrashed()->count();

                // Hitung berapa banyak checklist yang masih dihapus untuk semua title checklists di kartu ini
                $softDeletedChecklist = Checklists::whereIn('title_checklists_id', function ($query) use ($cardId) {
                    $query->select('id')->from('title_checklists')->where('cards_id', $cardId);
                })->onlyTrashed()->count();

                return response()->json([
                    'message' => 'Berhasil menghapus checklist permanen!',
                    'softDeletedTitle' => $softDeletedTitle,
                    'softDeletedChecklist' => $softDeletedChecklist,
                    'cardId' => $cardId,
                    'titlechecklist' => $titleChecklist,
                    'checklists' => $checklistsData
                ]);
            } else {
                return response()->json([
                    'message' => 'Title Checklist tidak ditemukan.',
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Checklist tidak ditemukan.',
            ], 404);
        }
    }
    // /Fitur Hapus Checklist Permanen //

    // Fitur Pemanggilan Data Title & Checklist //
    public function dataPulihkanTitleChecklist(Request $request)
    {
        // Asumsi title checklist memiliki bidang cards->id
        $cardId = $request->card_id;

        // Mendapatkan data title checklist
        $dataTitleRecover = TitleChecklists::onlyTrashed()->where('cards_id', $cardId)->get();

        // Mendapatkan data checklist
        $dataChecklistRecover = Checklists::onlyTrashed()->whereHas('titleChecklist', function ($query) use ($cardId) {
            $query->where('cards_id', $cardId);
        })->with('titleChecklist')->get();

        // Menyertakan data name dari TitleChecklists
        $dataChecklistRecover->each(function ($checklist) {
            $checklist->title_checklist_name = $checklist->titleChecklist->name;
        });

        return response()->json([
            'dataTitleRecover' => $dataTitleRecover,
            'dataChecklistRecover' => $dataChecklistRecover,
            'softDeletedTitle' => $dataTitleRecover->count(),
            'softDeletedChecklist' => $dataChecklistRecover->count(),
            'cardId' => $cardId
        ]);
    }
    // /Fitur Pemanggilan Data Title & Checklist //

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

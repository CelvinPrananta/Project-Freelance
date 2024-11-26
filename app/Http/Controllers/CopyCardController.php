<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Column;
use App\Models\TitleChecklists;
use App\Models\Checklists;
use App\Models\ModeAplikasi;
use Carbon\Carbon;

class CopyCardController extends Controller
{
    // Untuk menyalin kartu, judul ceklist dan ceklist
    public function copyCard(Request $request, $column_id, $id)
    {
        // Validasi inputan
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Temukan kartu asli atau tiruan
        $card = Card::findOrFail($id);

        // Tentukan posisi baru
        $newPosition = $card->position != 0 ? $card->position + 1 : 0;

        // Perbarui posisi kartu lain di kolom yang sama
        Card::where('column_id', $column_id)
            ->where('position', '>=', $newPosition)
            ->increment('position');

        // Salin Kartu
        $newCard = $card->replicate();
        $newCard->name = $request->input('name');
        $newCard->position = $newPosition;
        $newCard->created_at = Carbon::now();
        $newCard->save();

        $checklists = [];
        // dd($request->keep_checklists);
        if ($request->input('keep_checklists')) {
            $titleChecklists = TitleChecklists::where('cards_id', $id)->get();
            $checklists = [];
            foreach ($titleChecklists as $titleChecklist) {
                $newTitleChecklist = $titleChecklist->replicate();
                $newTitleChecklist->cards_id = $newCard->id;
                $newTitleChecklist->created_at = Carbon::now();

                // Jika checkbox tidak tercentang, set nilai percentage
                if (!$request->input('keep_checklists')) {
                    $newTitleChecklist->percentage = NULL;
                }

                $newTitleChecklist->save();

                // Salin Ceklist
                $checklists = Checklists::where('title_checklists_id', $titleChecklist->id)->get();
                foreach ($checklists as $checklist) {
                    $newChecklist = $checklist->replicate();
                    $newChecklist->title_checklists_id = $newTitleChecklist->id;
                    $newChecklist->created_at = Carbon::now();

                    // Jika checkbox tidak tercentang, set nilai is_active
                    // if (!$request->input('keep_checklists')) {
                    //     $newChecklist->is_active = 0;
                    // }

                    $newChecklist->save();
                }
            }
        }
        // Salin Judul Ceklist


        // Mendapatkan Data Kolom
        $newColumn = Column::findOrFail($column_id);

        // Untuk icon checklist
        $titleChecklistID = TitleChecklists::where('cards_id', $card->id)->pluck('id');
        $perChecklist = $request->input('keep_checklists') ? Checklists::whereIn('title_checklists_id', $titleChecklistID)->where('is_active', 1)->count() : 0;
        $jumlahChecklist = Checklists::whereIn('title_checklists_id', $titleChecklistID)->count();

        // Hitung persentase
        $totalPercentage = !empty($perChecklist) ? round(($perChecklist / $jumlahChecklist) * 100) : 0;

        // Mendapatkan tema aplikasi
        $dataTema = ModeAplikasi::where('user_id', auth()->user()->user_id)->pluck('tema_aplikasi');
        $result_tema = ModeAplikasi::whereIn('tema_aplikasi', $dataTema)->where('user_id', auth()->user()->user_id)->get();
        $tema_aplikasi = $result_tema->pluck('tema_aplikasi');

        // Siapkan respons
        $responseData = [
            'message' => 'Berhasil menyalin kartu!',
            'new_card_id' => $newCard->id,

            'checklists' => $checklists,

            'column' => [
                'id' => $newColumn->id,
                'name' => $newColumn->name,
            ],

            'card' => [
                'id' => $newCard->id,
                'name' => $newCard->name,
                'pattern' => $newCard->pattern,
                'description' => $newCard->description,
                'position' => $newCard->position,
                'updateUrl' => route('perbaharuiKartu', ['card_id' => $newCard->id]),
                'deleteUrl' => route('hapusKartu', ['card_id' => $newCard->id]),
                'copyCardUrl' => route('copyCard', ['column_id' => $newColumn->id, 'id' => $newCard->id])
            ],

            'perChecklist' => $perChecklist,
            'jumlahChecklist' => $jumlahChecklist,
            'totalPercentage' => $totalPercentage,
            'result_tema' => [
                'tema_aplikasi' => $tema_aplikasi
            ]
        ];

        return response()->json($responseData, 200);
    }

    // Untuk mendapatkan total ceklist
    public function getTotalActiveChecklists($id)
    {
        $totalActiveChecklists = Checklists::whereHas('titleChecklist', function ($query) use ($id) {
            $query->where('cards_id', $id);
        })->where('is_active', 1)->count();

        return response()->json(['totalActiveChecklists' => $totalActiveChecklists]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Checklists;
use App\Models\Column;
use App\Models\TitleChecklists;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akun = ["❰ Annisa Jarizky ❱", "Bhinti Nur Khasanah", "🎼 Dhyaa ⊳ 0:35 ─❍─── -1:02", "Devi Thalia A ♥‿♥",
            "Fiki Aviantono", "Ainun Hasri", "✧･ﾟ: *✧Astrid Dyssa✧*:･ﾟ✧", "Adeline Labaiqa Anindiani",
            "Imam Ghazali", "Muhammad Nabil Islam", "Dinda Putri 𓆝 𓆟 𓆞 𓆝 𓆟", "Agung Dewantara",
            "°❀⋆ Olsavira Agsa P °ೃ࿔*:･", "Nur Aini Ersanti ¯\_◉‿◉_/¯", "Nur Alfiyah Miftakhul Jannah", "Alvisi Aura Chandra",
            "Muhammad Zulqarnain Hidayat", "Jovi Nisita Pratama", "Tiara Amalinda Prabawanti", "Riris Amalia S •⁠ᴗ⁠•⁠",
            "Putri Regita Saptaningtias", "Evi Hidayatun Nafiah", "Anggarda Bayu W", "Firzaq Emir Pranowo",
            "Andre Setiawan Ahmadi (Intern_Programmer)", "Celvin Prananta Ginting", "Alya Dwi Safitri"];

        $tgl = ["19 Juni - 21 Juni 2024", "24 Juni - 28 Juni 2024", "1 Juli - 5 Juli 2024", "8 Juli - 12 Juli 2024", "15 Juli - 19 Juli 2024", "22 Juli - 26 Juli 2024", "29 Juli - 2 Agustus 2024"];

        $hari = ['Target Mingguan','Senin','Selasa','Rabu','Kamis','Jumat'];

            foreach($akun as $value){
                $column = Column::create([
                    'board_id' => 1,
                    'name' => $value,
                ]);
                foreach($tgl as $value2){
                    $card = Card::create([
                        'column_id' => $column->id,
                        'name' => $value2,
                    ]);
                    foreach($hari as $value3){
                        TitleChecklists::create([
                            'cards_id' => $card->id,
                            'name' => $value3,
                        ]);
                    }
                }
            }
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mode_aplikasi', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('tema_aplikasi')->nullable();
            $table->string('warna_sistem')->nullable();
            $table->string('warna_sistem_tulisan')->nullable();
            $table->string('warna_mode')->nullable();
            $table->string('tabel_warna')->nullable();
            $table->string('tabel_tulisan_tersembunyi')->nullable();
            $table->string('warna_dropdown_menu')->nullable();
            $table->string('ikon_plugin')->nullable();
            $table->string('bayangan_kotak_header')->nullable();
            $table->string('warna_mode_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mode_aplikasi');
    }
};
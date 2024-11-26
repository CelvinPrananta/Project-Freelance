<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('title_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('cards_id')->constrained('cards')->onDelete('cascade');
            $table->double('percentage')->nullable()->default(0);
            $table->integer('position')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_checklists');
    }
};
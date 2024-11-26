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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->longText("description")->nullable();
            $table->string("pattern")->nullable();

            $table->foreignId("column_id")->constrained()->onDelete("cascade");
            $table->unsignedBigInteger("previous_id")->nullable();
            $table->unsignedBigInteger("next_id")->nullable();
            $table->integer('position')->default(0);
            $table->softDeletes();

            $table->foreign('previous_id')->references('id')->on('cards')->onDelete("set null");
            $table->foreign('next_id')->references('id')->on('cards')->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
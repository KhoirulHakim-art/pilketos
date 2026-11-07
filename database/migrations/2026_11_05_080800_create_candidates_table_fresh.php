<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_urut')->unique();
            $table->string('nama'); // Nama individu calon
            $table->text('visi');
            $table->text('misi');
            $table->string('foto')->nullable();
            $table->integer('total_votes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
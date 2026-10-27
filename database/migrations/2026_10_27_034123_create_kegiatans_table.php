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
        Schema::create('kegiatan', function (Blueprint $table) {
    $table->id();
    $table->char('kode_kegiatan', 10)->unique();
    $table->string('nama_kegiatan', 100);
    $table->string('email_pic', 100)->unique();
    $table->unsignedSmallInteger('kuota');
    $table->foreignId('jenis_kegiatan_id')
        ->constrained('jenis_kegiatan')
        ->cascadeOnUpdate()
        ->restrictOnDelete();
    $table->timestamps();
});

// Tambahkan CHECK constraint setelah tabel dibuat
        DB::statement(
            'ALTER TABLE kegiatan
             ADD CONSTRAINT chk_kegiatan_kuota
             CHECK (kuota BETWEEN 10 AND 200)'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'kode_kegiatan',
        'nama_kegiatan',
        'email_pic',
        'kuota',
        'jenis_kegiatan_id',
    ];

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class);
    }
}
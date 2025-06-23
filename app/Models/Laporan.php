<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan';
    protected $fillable = [
        'user_id',
        'judul',
        'catatan',
        'jenis_surat',
        'status_filter',
        'tanggal_dari',
        'tanggal_sampai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    use HasFactory;

    protected $table = 'hki';

    protected $fillable = [
        'user_id',
        'namaPemHki',
        'alamatPemHki',
        'judulInvensi',
        'tanggalPemHki',
        'statusSurat',
        'nomorSurat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventors()
    {
        return $this->hasMany(Inventor::class);
    }
}

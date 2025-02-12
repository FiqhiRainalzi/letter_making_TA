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
        'namaPemegang',
        'alamat',
        'judul',
        'inventor1',
        'inventor2',
        'inventor3',
        'inventor4',
        'inventor5',
        'inventor6',
        'inventor7',
        'inventor8',
        'inventor9',
        'inventor10',
        'bidangStudi1',
        'bidangStudi2',
        'bidangStudi3',
        'bidangStudi4',
        'bidangStudi5',
        'bidangStudi6',
        'bidangStudi7',
        'bidangStudi8',
        'bidangStudi9',
        'bidangStudi10',
        'tanggal',
        'statusSurat',
        'nomorSurat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

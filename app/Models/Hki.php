<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hki extends Model
{
    use HasFactory;
    protected $table = 'hki';
    protected $fillable = [
            'namaPemHki'   ,
            'alamatPemHki' ,
            'judulInvensi'  ,
            'namaInventor1' ,
            'bidangStudi1'  ,
            'namaInventor2' ,
            'bidangStudi2'  ,
            'namaInventor3' ,
            'bidangStudi3'  ,
            'namaInventor4' ,
            'bidangStudi4'  ,
            'namaInventor5' ,
            'bidangStudi5'  ,
            'namaInventor6' ,
            'bidangStudi6'  ,
            'namaInventor7' ,
            'bidangStudi7'  ,
            'namaInventor8' ,
            'bidangStudi8'  ,
            'tanggalPemHki' 
    ];
}

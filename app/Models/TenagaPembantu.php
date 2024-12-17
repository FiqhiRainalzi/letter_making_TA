<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenagaPembantu extends Model
{
    use HasFactory;

    protected $fillable = ['pkm_id', 'penelitian_id', 'nama', 'status'];
    protected $table = 'tenaga_pembantu';

    public function pkm()
    {
        return $this->belongsTo(Pkm::class);
    }

    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class);
    }
}

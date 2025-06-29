<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'status'];

    // Relasi dengan pengguna
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

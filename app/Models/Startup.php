<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Startup extends Model
{
    use HasFactory;
    protected $table = 'startup';
    protected $fillable = ['user_id', 'logo_startup','phone_number'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

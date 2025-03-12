<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Coach extends Model
{
    use HasFactory;
    protected $table = 'coach';
    protected $fillable = ['user_id', 'phone_number', 'diplome', 'competence', 'description', 'profile_image', 'cover_image', 'document'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

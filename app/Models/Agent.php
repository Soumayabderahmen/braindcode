<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'description',
        'image',
        'type',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}

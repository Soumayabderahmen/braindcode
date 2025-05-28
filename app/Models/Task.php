<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   use HasFactory;

    protected $fillable = [
        'section_id',
        'content',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}

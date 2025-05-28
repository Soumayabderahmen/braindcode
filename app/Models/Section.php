<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
 use HasFactory;

    protected $fillable = [
        'agent_id',
        'title',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }}

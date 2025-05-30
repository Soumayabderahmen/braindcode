<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intention extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'example_message', 'prompt_template'];

}

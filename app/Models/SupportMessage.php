<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model {
    use HasFactory;

    protected $fillable = ['name', 'email', 'message', 'category', 'file_path','status'];
}

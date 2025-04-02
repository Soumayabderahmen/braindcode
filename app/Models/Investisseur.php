<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investisseur  extends Model
{
    use HasFactory;
    protected $table = 'investisseur';

    protected $fillable = ['user_id', 'video_presentation', 'description', 'website_link', 'social_links', 'profile_image', 'cover_image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }}

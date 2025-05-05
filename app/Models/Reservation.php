<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table = 'reservation';

    protected $fillable = [
        'coach_id',
        'startup_id',
        'meeting_time',
        'duration',
        'total',
        'message',
        'statut',
        'disponibilite_id',
    ];

    // Relations

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
    public function disponibilite()
{
    return $this->belongsTo(Disponibilite::class);
}

}

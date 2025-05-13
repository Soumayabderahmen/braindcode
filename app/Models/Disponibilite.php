<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    use HasFactory;
    protected $table = 'disponibilite';

    protected $fillable = [
        'coach_id',
        'day_of_week',
        'date',
        'start_time',
        'end_time',
        'statut',
        'honoraire',
        'nb_place',
        'titre',
        'type_formation',
    ];

    /**
     * Relation : une disponibilité appartient à un coach.
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}

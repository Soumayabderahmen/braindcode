<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'reservation_id',
        'pays',
        'ville',
        'adresse',
        'code_postal',
        'total',
        'currency',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'status',
    ];
public function user(){
    return $this->belongsTo(User::class);
}

public function reservation(){
    return $this->belongsTo(Reservation::class);
}
}

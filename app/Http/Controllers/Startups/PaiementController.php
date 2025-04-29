<?php

namespace App\Http\Controllers\Startups;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;
class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createStripeIntent(Request $request)
    {
        try {
            Log::info('Création du Stripe PaymentIntent', $request->all());
    
            $user = Auth::user(); // Utilisateur connecté
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }
    
            $reservation = Reservation::find($request->reservation_id);
            if (!$reservation) {
                return response()->json(['error' => 'Réservation introuvable'], 404);
            }
    
            Stripe::setApiKey(config('services.stripe.secret'));
    
            $amount = $reservation->total * 100; // En centimes pour Stripe
    
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) $amount,
                'currency' => $reservation->currency ?? 'eur',
                'metadata' => [
                    'user_id' => $user->id,
                    'reservation_id' => $reservation->id,
                ],
            ]);
    
            // Enregistre un paiement en attente (optionnel)
            Paiement::create([
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'Pays' => $request->pays ?? 'France',
                'ville' => $request->ville ?? '',
                'adresse' => $request->adresse ?? '',
                'code_postal' => $request->code_postal ?? '',
                'total' => $reservation->total,
                'currency' => $reservation->currency ?? 'eur',
                'stripe_session_id' => $paymentIntent->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'en_attente',
            ]);
    
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création Stripe Intent: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($reservationId)
    {
        $reservation = Reservation::with('startup')->findOrFail($reservationId);
    
        return Inertia::render('Paiements/Paiement', [
            'reservation' => $reservation,
            // 'user' => Auth::user(),
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

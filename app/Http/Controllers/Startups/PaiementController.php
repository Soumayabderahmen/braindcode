<?php

namespace App\Http\Controllers\Startups;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function show(Reservation $reservation)
    {
        return view('paiement.show', compact('reservation'));
    }

    public function checkout(Request $request, Reservation $reservation)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // ✅ Validation des données du formulaire
        $validated = $request->validate([
            'pays' => 'required|string|max:100',
            'ville' => 'required|string|max:100',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
        ]);

        // ✅ Création de la session Stripe Checkout
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Réservation coach',
                    ],
                    'unit_amount' => $reservation->total * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            // ✅ Ne surtout pas passer une route avec {reservation}
            'success_url' => route('startup.paiement.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('startup.paiement.cancel'),
        ]);

        // ✅ Enregistrement du paiement dans la BDD
        Paiement::create([
            'user_id' => Auth::id(),
            'reservation_id' => $reservation->id,
            'pays' => $validated['pays'],
            'ville' => $validated['ville'],
            'adresse' => $validated['adresse'],
            'code_postal' => $validated['code_postal'],
            'total' => $reservation->total,
            'currency' => 'eur',
            'stripe_session_id' => $session->id,
            'status' => 'en_attente',
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session_id = $request->get('session_id');

        if (!$session_id) {
            abort(400, 'Session ID manquant');
        }

        // ✅ Récupère le paiement lié à cette session Stripe
        $paiement = Paiement::where('stripe_session_id', $session_id)->firstOrFail();

        // ✅ Marque la réservation comme "payée"
        $reservation = $paiement->reservation;
        $reservation->paid = true;
        $reservation->save();

        // ✅ Met à jour le statut du paiement
        $paiement->status = 'payé';
        $paiement->save();

        return view('paiement.success', compact('reservation'));
    }

    public function cancel()
    {
        return view('paiement.cancel');
    }
}

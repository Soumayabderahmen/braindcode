<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement de la réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 8px 0;
        }
        .btn-pay {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-pay:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Paiement pour votre réservation</h2>

        <div class="details">
            <p><strong>Nom :</strong> {{ $reservation->startup->user->name ?? 'Utilisateur' }}</p>
            <p><strong>Date du meeting :</strong> {{ \Carbon\Carbon::parse($reservation->meeting_time)->translatedFormat('d F Y à H:i') }}</p>
            <p><strong>Durée :</strong> {{ $reservation->duration }} minutes</p>
            <p><strong>Montant :</strong> <span style="color: #28a745">{{ $reservation->total }} €</span></p>
        </div>

        <form action="{{ route('paiement.checkout', $reservation->id) }}" method="POST">
            @csrf
            <button class="btn-pay">Payer maintenant 💳</button>
        </form>
    </div>
</body>
</html>

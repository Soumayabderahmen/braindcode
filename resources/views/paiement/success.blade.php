<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement réussi</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            padding: 40px;
            text-align: center;
        }
        .card {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: #28a745;
            font-size: 26px;
        }
        p {
            font-size: 16px;
            color: #444;
            margin-bottom: 10px;
        }
        a.button {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #0056b3;
        }
        .whereby-link {
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>✅ Paiement effectué avec succès !</h1>

        <p>Merci {{ $reservation->startup->user->name ?? 'utilisateur' }} pour votre confiance.</p>
        <p>Votre réservation est désormais <strong>confirmée</strong>.</p>

        <div class="whereby-link">
            <p><strong>Votre lien de réunion :</strong></p>
            @if ($reservation->meeting_url)
                <a href="{{ $reservation->meeting_url }}" target="_blank">
                    {{ $reservation->meeting_url }}
                </a>
            @else
                <p><em>Le lien sera disponible prochainement.</em></p>
            @endif
        </div>

        {{-- <a href="{{ route('startup.dashboard') }}" class="button">Retour au tableau de bord</a> --}}
    </div>

</body>
</html>

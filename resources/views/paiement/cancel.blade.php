<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement annulé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            padding: 40px;
            text-align: center;
        }
        .card {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #dc3545;
        }
        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        a.button {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 5px;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>❌ Paiement annulé</h1>
        <p>Votre paiement n’a pas été finalisé.<br> Vous pouvez réessayer à tout moment.</p>

        <a href="{{ route('startup.paiement.show', session('reservation_id', 1)) }}" class="button">Revenir au paiement</a>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Paiement de la réservation</h2>

    <p><strong>Montant :</strong> {{ $reservation->total }} €</p>

    <form action="{{ route('startup.paiement.checkout', $reservation->id) }}" method="POST">
        @csrf

        <label for="pays">Pays</label>
        <input type="text" name="pays" id="pays" required>

        <label for="ville">Ville</label>
        <input type="text" name="ville" id="ville" required>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse" required>

        <label for="code_postal">Code postal</label>
        <input type="text" name="code_postal" id="code_postal" required>

        <button type="submit">Procéder au paiement 💳</button>
    </form>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Inscription d'un nouveau  investisseur</title>
</head>
<body>
    <h2>Un nouveau investisseur s'est inscrit !</h2>
    <p><strong>Nom :</strong> {{ $investisName }}</p>
    <p><strong>Email :</strong> {{ $investisEmail }}</p>
    <p><strong>Numéro du Téléphone :</strong> {{ $investisNumber }}</p>

    <p><strong>Visibilité :</strong> {{ $investisVisibility }}</p>
 
    <p>Merci de vérifier son compte et de l'activer si nécessaire.</p>
    <a href="{{ $activationLink }}" 
    style="display: inline-block; padding: 10px 20px; color: white; background-color: #007bff; text-decoration: none; border-radius: 5px;">
     Activer l'investisseur
 </a>
</body>
</html>

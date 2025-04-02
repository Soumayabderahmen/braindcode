<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Inscription d'un nouveau  Coach</title>
</head>
<body>
    <h2>Un nouveau coach s'est inscrit !</h2>
    <p><strong>Nom :</strong> {{ $coachName }}</p>
    <p><strong>Email :</strong> {{ $coachEmail }}</p>
    <p><strong>Numéro du Téléphone :</strong> {{ $coachNumber }}</p>

    <p><strong>Specialité :</strong> {{ $coachSpecialite }}</p>

 
    <p>Merci de vérifier son compte et de l'activer si nécessaire.</p>
    <a href="{{ $activationLink }}" 
    style="display: inline-block; padding: 10px 20px; color: white; background-color: #007bff; text-decoration: none; border-radius: 5px;">
     Activer le Coach
 </a>
</body>
</html>

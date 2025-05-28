<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statut de votre réservation</title>
</head>
<body>
    <h2>Bonjour {{ $reservation->startup->user->name ?? 'Utilisateur' }},</h2>

    @if($reservation->statut === 'acceptée')
        <p>🎉 Votre réservation a été <strong>acceptée</strong> par le coach !</p>

        <p><strong>Date et heure du meeting :</strong> {{ \Carbon\Carbon::parse($reservation->meeting_time)->translatedFormat('d F Y à H:i') }}</p>
        <p><strong>Durée :</strong> {{ $reservation->duration }} minutes</p>
        <p><strong>Coût total :</strong> {{ $reservation->total }} €</p>
      @if(!$reservation->paid)
    <p><strong>Lien Whereby :</strong> ⚠️ Le lien sera activé après le paiement.</p>
    <form action="{{ route('paiement.show', $reservation->id) }}" method="GET">
        @csrf
        <button type="submit">Procéder au paiement 💳</button>
    </form>
@else
    <p><strong>Lien Whereby :</strong> <a href="{{ $reservation->meeting_url }}">{{ $reservation->meeting_url }}</a></p>
@endif
        </form>

        <p>Merci de votre confiance 🙏</p>

    @elseif($reservation->statut === 'refusée')
        <p>❌ Nous sommes désolés, votre réservation a été <strong>refusée</strong>.</p>
        <p>Motif : indisponibilité du coach à la date et l'heure souhaitées ou autre contrainte.</p>
        <p>N'hésitez pas à choisir un autre créneau disponible.</p>
        <p>Merci de votre compréhension.</p>
    @else
        <p>Le statut de votre réservation a été mis à jour : <strong>{{ ucfirst($reservation->statut) }}</strong></p>
    @endif
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau lead</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h1>Nouveau lead recu</h1>
    <p><strong>Nom :</strong> {{ $lead->name }}</p>
    <p><strong>Telephone :</strong> {{ $lead->phone }}</p>
    <p><strong>Email :</strong> {{ $lead->email ?? 'Non renseigne' }}</p>
    <p><strong>Ville :</strong> {{ $lead->city_label ?? ($lead->city?->name ?? 'Non renseignee') }}</p>
    <p><strong>Code postal :</strong> {{ $lead->postal_code ?? 'Non renseigne' }}</p>
    <p><strong>Service :</strong> {{ $lead->service_requested ?? ($lead->service?->name ?? 'Non renseigne') }}</p>
    <p><strong>Urgence :</strong> {{ $lead->urgency_level }}</p>
    <p><strong>Page source :</strong> {{ $lead->source_url ?? 'Non renseignee' }}</p>
    <p><strong>Mot-cle cible :</strong> {{ $lead->keyword_targeted ?? 'Non renseigne' }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $lead->message ?? 'Aucun message.' }}</p>
</body>
</html>

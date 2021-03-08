<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
</head>
<body>
  <div class="text-center">
    <h1>{{ $subject }} ! {{ $smiley }}　</h1>
    <p>
    Bonjour {{ $email }},<br>
    Votre {{ $status }} à l'établissement : {{ $establishmentInfos['name'] }} le {{ $date }} pour le créneau horaire de {{ $time }} est confirmé !<br><br>
    @if($status == 'réservation' && $route == "web")
    <a href="https://fakeaffluences.herokuapp.com/reservation/annulation/<?= $token ;?>">Annuler ma réservation</a><br>
    @elseif(($status == 'réservation' && $route == "api"))
    Votre token d'annulation : <?= $token ;?>
    @endif
    Très bonne journée à vous,<br>
    Cordialement,<br>
    L'équipe fakeaffluences7851
    </p>
  </div>
</body>
</html>
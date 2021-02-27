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
    token d'annulation : {{ $token }}
    Très bonne journée à vous,<br>
    Cordialement,<br>
    L'équipe fakeaffluences7851
    </p>
  </div>
</body>
</html>
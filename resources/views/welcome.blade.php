@extends('layouts.default')
@section('content')

@if ($token)
<div class="alert alert-success text-center">
    <h4>Votre demande de réservation a bien été affectué !</h4><br>
    <p>
        J'ai l'immense honneur de vous annoncer l'approbation de votre demande de réservation à l'établissement suivant : {{ $establishmentInfos['name'] }} !<br>
        Si toutefois vous souhaitez annuler cette réservation, rendez-vous sur la page suivante accessible grâce au lien ci-dessous !<br><br><br>
        <a href="/reservation/annulation/<?= $token ;?>">Annuler ma réservation</a>
    </p>
</div>
@endif
@if (session('deleted'))
<div class="alert alert-success text-center">
    <h4>Votre demande d'annulation a bien été affectué !</h4><br>
    <p>
        {{ session('deleted') }}
    </p>
</div>
@endif
@if (session('denied'))
<div class="alert alert-danger text-center">
    <h4>Erreur... !</h4><br>
    <p>{{ session('denied') }}</p>
</div>
@endif
<div class="description">
    <div>
        <p class="text-justify"><?= $establishmentInfos['description'] ;?></p>
    </div>
    <div>
        <img src="{{url('/assets/img/VHive_gaming_room.jpg')}}" alt="V.Hive gaming room" id="showcase_VHive"/>
    </div>
</div>
<div class="schedule text-center">
    <h1>Horaires</h1>
    <br>
    <div>
        <table>
            <thead>
                <tr>
                @foreach ($establishmentInfos['schedule']['days'] as $day)
                    <th>{{ $day }}</th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12h - 17h</td>
                    <td>12h - 17h</td>
                    <td>12h - 17h</td>
                    <td>12h - 17h</td>
                    <td>12h - 17h</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <a href="/reservation"><button>Réserver</button></a>
</div>
<div id="map"></div>
<script src="{{url('/assets/js/main.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= env('API_GOOGLE_KEY') ;?>&callback=initMap&libraries=&v=weekly" async></script>
@endsection
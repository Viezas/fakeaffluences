@extends('layouts.default')
@section('content')

<div>
  @if (session('denied'))
  <div class="alert alert-danger">
      <ul>
        <li>{{ session('denied') }}</li>
      </ul>
  </div>
  @endif
  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif
  <form action="" method="POST" class="reservation">
    @csrf
    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="E-mail" autofocus><br>
    <input type="date" name="date" id="date" value="<?= old('date') ? old('date') : date("Y-m-d") ;?>" min="<?= date("Y-m-d");?>"/><br>
    <input type="time" name="time" id="time" value="<?= old('time') ? old('time') : date("H").":00" ;?>" step="01:00"/>
    <div id="cgu">
      <input type="checkbox" name="cgu" id="cgu2">
      <label for="cgu2">Accepter les <a href="#">conditions d'utilisation</a></label>
    </div>
    <button type="submit">Réserver mon créneau !</button>
  </form>
</div>

@endsection
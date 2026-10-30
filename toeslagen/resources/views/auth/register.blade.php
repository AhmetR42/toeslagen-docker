@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Account aanmaken</h2>
  <form method="POST" action="{{ url('/registreren') }}">
    @csrf
    <label for="name">Volledige naam</label>
    <input id="name" name="name" value="{{ old('name') }}" required>
    <label for="email">E-mailadres</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
    <label for="password">Wachtwoord (min. 8 tekens)</label>
    <input id="password" name="password" type="password" required>
    <label for="password_confirmation">Herhaal wachtwoord</label>
    <input id="password_confirmation" name="password_confirmation" type="password" required>
    @if($errors->any())<div class="msg err">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>@endif
    <button class="btn">Account aanmaken</button>
  </form>
  <p class="hint">Heb je al een account? <a href="{{ route('login') }}">Inloggen</a></p>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Inloggen</h2>
  <form method="POST" action="{{ url('/inloggen') }}">
    @csrf
    <label for="email">E-mailadres</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
    <label for="password">Wachtwoord</label>
    <input id="password" name="password" type="password" required>
    @error('email')<div class="msg err">{{ $message }}</div>@enderror
    <button class="btn">Inloggen</button>
  </form>
  <p class="hint">Nog geen account? <a href="{{ route('register') }}">Account aanmaken</a></p>
</div>
@endsection

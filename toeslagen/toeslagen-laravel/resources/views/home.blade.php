@extends('layouts.app')
@section('content')
<div class="card">
  <h2>Welkom, {{ auth()->user()->name }}</h2>
  <p>Je bent ingelogd als <span class="tag">{{ \App\Models\User::ROLLEN[auth()->user()->rol] }}</span></p>
</div>
@endsection

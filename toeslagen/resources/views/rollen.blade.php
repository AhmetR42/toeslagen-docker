@extends('layouts.app')
@section('content')
@php
$rechten = [
  ['Eigen account bekijken', 1, 1, 1],
  ['Toeslagaanvraag indienen', 1, 0, 0],
  ['Aanvragen beoordelen', 0, 1, 1],
  ['Gebruikers bekijken', 0, 1, 1],
  ['Rollen wijzigen', 0, 0, 1],
  ['Gebruikers blokkeren / verwijderen', 0, 0, 1],
];
@endphp
<div class="card wide">
  <h2>Rollen &amp; rechten</h2>
  <table>
    <tr><th>Recht</th><th>Burger</th><th>Medewerker</th><th>Admin</th></tr>
    @foreach($rechten as $r)
      <tr><td>{{ $r[0] }}</td>
      @foreach([1,2,3] as $i)<td class="c {{ $r[$i] ? 'yes' : 'no' }}">{{ $r[$i] ? '✓' : '✗' }}</td>@endforeach
      </tr>
    @endforeach
  </table>
</div>
@endsection

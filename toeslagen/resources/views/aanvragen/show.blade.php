@extends('layouts.app')
@section('content')
@include('toeslagen._stijl')

<div class="card">
@include('toeslagen._icoon', ['slug' => $aanvraag->toeslag->slug])
<h2>Aanvraag {{ $aanvraag->toeslag->naam }}</h2>
<p>Status: <span class="tag status-{{ $aanvraag->status }}">{{ $aanvraag->statusLabel() }}</span></p>

{{-- Voortgang: ingediend → in behandeling → besluit --}}
@php $besluit = in_array($aanvraag->status, ['goedgekeurd', 'afgewezen']); @endphp
<ol class="stappen">
<li class="klaar">Ingediend</li>
<li class="klaar">In behandeling</li>
<li class="{{ $besluit ? 'klaar' : '' }} {{ $aanvraag->status === 'afgewezen' ? 'rood' : '' }}">
{{ $besluit ? $aanvraag->statusLabel() : 'Besluit' }}
</li>
</ol>

<table class="gegevens">
<tr><th>Ingediend op</th><td>{{ $aanvraag->created_at->format('d-m-Y H:i') }}</td></tr>
<tr><th>Jaarinkomen</th><td>€ {{ number_format($aanvraag->inkomen, 2, ',', '.') }}</td></tr>
<tr><th>Huishouden</th><td>{{ $aanvraag->huishouden }} {{ $aanvraag->huishouden == 1 ? 'persoon' : 'personen' }}</td></tr>
@if(!is_null($aanvraag->huur_per_maand))
<tr><th>Huur per maand</th><td>€ {{ number_format($aanvraag->huur_per_maand, 2, ',', '.') }}</td></tr>
@endif
@if(!is_null($aanvraag->aantal_kinderen))
<tr><th>Aantal kinderen</th><td>{{ $aanvraag->aantal_kinderen }}</td></tr>
<tr><th>Opvanguren per maand</th><td>{{ $aanvraag->opvang_uren }}</td></tr>
@endif
@if(!is_null($aanvraag->zorgverzekering))
<tr><th>Nederlandse zorgverzekering</th><td>{{ $aanvraag->zorgverzekering ? 'Ja' : 'Nee' }}</td></tr>
@endif
</table>

<p class="hint"><a href="{{ route('toeslagen.index') }}">← Terug naar alle toeslagen</a></p>
</div>
@endsection

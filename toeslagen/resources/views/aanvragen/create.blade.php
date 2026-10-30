@extends('layouts.app')
@section('content')
@include('toeslagen._stijl')

<div class="card">
@include('toeslagen._icoon', ['slug' => $toeslag->slug])
<h2>{{ $toeslag->naam }} aanvragen</h2>
<p class="hint" style="margin-top:0">{{ $toeslag->omschrijving }}</p>

<form method="POST" action="{{ route('aanvragen.store', $toeslag) }}">
@csrf
<label for="inkomen">Jaarinkomen (€)</label>
<input id="inkomen" name="inkomen" type="number" min="0" step="0.01" value="{{ old('inkomen') }}" required>

<label for="huishouden">Aantal personen in je huishouden</label>
<input id="huishouden" name="huishouden" type="number" min="1" max="20" value="{{ old('huishouden', 1) }}" required>

@if($toeslag->slug === 'huurtoeslag')
<label for="huur_per_maand">Kale huur per maand (€)</label>
<input id="huur_per_maand" name="huur_per_maand" type="number" min="1" step="0.01" value="{{ old('huur_per_maand') }}" required>
@endif

@if($toeslag->slug === 'kinderopvangtoeslag')
<label for="aantal_kinderen">Aantal kinderen in de opvang</label>
<input id="aantal_kinderen" name="aantal_kinderen" type="number" min="1" max="10" value="{{ old('aantal_kinderen', 1) }}" required>
<label for="opvang_uren">Opvanguren per maand (per kind)</label>
<input id="opvang_uren" name="opvang_uren" type="number" min="1" max="230" value="{{ old('opvang_uren') }}" required>
@endif

@if($toeslag->slug === 'zorgtoeslag')
<label class="check" for="zorgverzekering">
<input id="zorgverzekering" name="zorgverzekering" type="checkbox" value="1" @checked(old('zorgverzekering'))>
<span>Ik heb een Nederlandse zorgverzekering</span>
</label>
@endif

@if($errors->any())
<div class="msg err">
@foreach($errors->all() as $fout)<div>{{ $fout }}</div>@endforeach
</div>
@endif

<button class="btn">Aanvraag indienen</button>
</form>
<p class="hint"><a href="{{ route('toeslagen.index') }}">← Terug naar alle toeslagen</a></p>
</div>
@endsection

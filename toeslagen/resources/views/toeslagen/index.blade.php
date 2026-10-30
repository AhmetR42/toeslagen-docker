@extends('layouts.app')
@section('content')
@include('toeslagen._stijl')

<div class="tegels">
@foreach($toeslagen as $toeslag)
<div class="tegel">
@include('toeslagen._icoon', ['slug' => $toeslag->slug])
<h2>{{ $toeslag->naam }}</h2>
<p>{{ $toeslag->omschrijving }}</p>
<ul class="voorwaarden">
@foreach($toeslag->voorwaarden as $voorwaarde)
<li>{{ $voorwaarde }}</li>
@endforeach
</ul>
<div class="grens">Inkomensgrens: € {{ number_format($toeslag->max_inkomen, 0, ',', '.') }} per jaar</div>

@auth
@if(auth()->user()->rol === 'burger')
<a class="btn" href="{{ route('aanvragen.create', $toeslag) }}">{{ $toeslag->naam }} aanvragen</a>
@endif
@else
<a class="btn" href="{{ route('login') }}">Log in om aan te vragen</a>
@endauth
</div>
@endforeach
</div>
@endsection

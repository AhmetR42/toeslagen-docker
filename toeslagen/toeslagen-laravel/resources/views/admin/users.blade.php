@extends('layouts.app')
@section('content')
<div class="card wide">
  <h2>Admin: gebruikersbeheer ({{ $users->count() }})</h2>
  <table>
    <tr><th>Naam</th><th>E-mail</th><th>Rol</th><th>Status</th><th>Acties</th></tr>
    @foreach($users as $u)
    <tr>
      <td>{{ $u->name }}</td>
      <td>{{ $u->email }}</td>
      <td>
        @if($u->id === auth()->id())
          <span class="tag">{{ \App\Models\User::ROLLEN[$u->rol] }}</span>
        @else
          <form method="POST" action="{{ route('admin.users.rol', $u) }}">
            @csrf @method('PATCH')
            <select name="rol" onchange="this.form.submit()">
              @foreach(\App\Models\User::ROLLEN as $k => $v)
                <option value="{{ $k }}" @selected($u->rol === $k)>{{ $v }}</option>
              @endforeach
            </select>
          </form>
        @endif
      </td>
      <td><span class="tag">{{ $u->geblokkeerd ? 'Geblokkeerd' : 'Actief' }}</span></td>
      <td>
        @if($u->id === auth()->id())
          <em>jij</em>
        @else
          <div class="row">
            <form method="POST" action="{{ route('admin.users.block', $u) }}">@csrf @method('PATCH')
              <button class="btn sm grey">{{ $u->geblokkeerd ? 'Deblokkeer' : 'Blokkeer' }}</button></form>
            <form method="POST" action="{{ route('admin.users.delete', $u) }}" onsubmit="return confirm('Verwijderen?')">@csrf @method('DELETE')
              <button class="btn sm red">Verwijder</button></form>
          </div>
        @endif
      </td>
    </tr>
    @endforeach
  </table>
</div>
@endsection

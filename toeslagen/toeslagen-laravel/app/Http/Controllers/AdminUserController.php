<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('admin.users', ['users' => User::orderBy('name')->get()]);
    }

    public function updateRole(Request $r, User $user)
    {
        abort_if($user->id === $r->user()->id, 403, 'Je kunt je eigen rol niet wijzigen.');
        $d = $r->validate(['rol' => ['required', Rule::in(array_keys(User::ROLLEN))]]);
        $user->forceFill($d)->save();

        return back()->with('ok', 'Rol bijgewerkt.');
    }

    public function toggleBlock(Request $r, User $user)
    {
        abort_if($user->id === $r->user()->id, 403, 'Je kunt jezelf niet blokkeren.');
        $user->forceFill(['geblokkeerd' => !$user->geblokkeerd])->save();

        return back()->with('ok', 'Status bijgewerkt.');
    }

    public function destroy(Request $r, User $user)
    {
        abort_if($user->id === $r->user()->id, 403, 'Je kunt jezelf niet verwijderen.');
        $user->delete();

        return back()->with('ok', 'Gebruiker verwijderd.');
    }
}

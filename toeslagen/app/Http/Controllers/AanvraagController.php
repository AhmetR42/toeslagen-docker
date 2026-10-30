<?php

namespace App\Http\Controllers;

use App\Http\Requests\AanvraagRequest;
use App\Models\Aanvraag;
use App\Models\Toeslag;
use Illuminate\Http\Request;

class AanvraagController extends Controller
{
    // Formulier voor huur-, zorg- of kinderopvangtoeslag
    public function create(Toeslag $toeslag)
    {
        return view('aanvragen.create', ['toeslag' => $toeslag]);
    }

    public function store(AanvraagRequest $request, Toeslag $toeslag)
    {
        $aanvraag = new Aanvraag($request->validated());
        $aanvraag->toeslag_id = $toeslag->id;
        $aanvraag->user_id = $request->user()->id;
        $aanvraag->status = 'in_behandeling';
        $aanvraag->save();

        return redirect()->route('aanvragen.show', $aanvraag)
            ->with('ok', "Je aanvraag voor {$toeslag->naam} is ingediend.");
    }

    // Status van één aanvraag
    public function show(Request $request, Aanvraag $aanvraag)
    {
        abort_unless($aanvraag->user_id === $request->user()->id, 403, 'Dit is niet jouw aanvraag.');

        return view('aanvragen.show', ['aanvraag' => $aanvraag->load('toeslag')]);
    }
}

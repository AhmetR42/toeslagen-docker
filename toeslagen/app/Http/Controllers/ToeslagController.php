<?php

namespace App\Http\Controllers;

use App\Models\Toeslag;

class ToeslagController extends Controller
{
    // Overzicht van alle beschikbare toeslagen (ook zichtbaar zonder in te loggen)
    public function index()
    {
        return view('toeslagen.index', ['toeslagen' => Toeslag::orderBy('naam')->get()]);
    }
}

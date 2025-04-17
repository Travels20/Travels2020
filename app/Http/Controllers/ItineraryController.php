<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;

class ItineraryController extends Controller
{
    public function index()
    {
        return view('itinerary');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'travel_date' => 'required|date',
        ]);

        Itinerary::create($request->all());

        return redirect('/itinerary')->with('success', 'Itinerary created!');
    }
}

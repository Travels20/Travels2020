<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itinerary;
use App\Models\vacation_summary;
use Illuminate\Support\Facades\DB;

class ItineraryController extends Controller
{
    public function admin()
    {
        return view('adminItinerary');
    }

    public function create()
    {
        return view('itinerary.create');
    }

    public function itineraryform()
    {
        return view('itinerary.itineraryform');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tripId' => 'nullable|string',
            'userName' => 'required|string',
            'tourName' => 'required|string',
            'checkIn' => 'required|date',
            'checkOut' => 'required|date',
            'numAdults' => 'required|integer',
            'numChildren' => 'nullable|integer',
            'cost' => 'nullable|string',
            'inclusion' => 'nullable|string',
            'exclusion' => 'nullable|string',
            'notes' => 'nullable|string',
            'hotel' => 'nullable|string',
            'flight' => 'nullable|string',
            'officerName' => 'nullable|string',
        ]);

        if (empty($validated['tripId'])) {
            $datePrefix = date('dmy');
            $latestTrip = DB::table('tour_booking')
                ->where('trip_id', 'like', "T2020{$datePrefix}%")
                ->orderByDesc('trip_id')
                ->first();
        
            $newDigits = $latestTrip
                ? str_pad((int) substr($latestTrip->trip_id, -4) + 1, 4, '0', STR_PAD_LEFT)
                : '1001';
        
            $generatedTripId = "T2020{$datePrefix}{$newDigits}";
            $validated['tripId'] = $generatedTripId;
        } else {
            $generatedTripId = $validated['tripId'];
        }
        // Log tripId for debugging
    \Log::info("Generated Trip ID: " . $generatedTripId);


        // File uploads
        if ($request->hasFile('timages')) {
            $validated['timages'] = $request->file('timages')->store('tour_images', 'public');
        }

        if ($request->hasFile('flightimages')) {
            $validated['flightimages'] = $request->file('flightimages')->store('flight_images', 'public');
        }

        if ($request->hasFile('officerimage')) {
            $validated['officerimage'] = $request->file('officerimage')->store('officer_image', 'public');
        }

        // Save to itinerary table
        $itinerary = tour_booking::create($validated);

        // Save to tour_booking table
        DB::table('tour_booking')->insert([
            'trip_id' => $generatedTripId,
            'username' => $validated['userName'],
            'tour_name' => $validated['tourName'],
            'check_in' => $validated['checkIn'],
            'check_out' => $validated['checkOut'],
            'adults' => $validated['numAdults'],
            'children' => $validated['numChildren'],
            'inclusion' => $validated['numChildren'],
            'exclusion' => $validated['numChildren'],
            'cost' => $validated['numChildren'],
            'tour_image' => $validated['numChildren'],
            'notes' => $validated['numChildren'],
            'hotel' => $validated['numChildren'],
            'flight' => $validated['numChildren'],
            'ftimage' => $validated['numChildren'],
            'officerName' => $validated['numChildren'],
            'officerimage' => $validated['numChildren'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Save daily itineraries
        if ($request->stay && $request->date && $request->itinerary) {
            foreach ($request->stay as $index => $stay) {
                $daily = new vacation_summary();
                $daily->itinerary_id = $itinerary->id;
                $daily->stay = $stay;
                $daily->date = $request->date[$index];
                $daily->itinerary = $request->itinerary[$index];

                if ($request->hasFile('images') && isset($request->file('images')[$index])) {
                    $daily->image = $request->file('images')[$index]->store('day_images', 'public');
                }

                $daily->save();
            }
        }

        return redirect('/adminItinerary')->with('success', 'Itinerary saved successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tour_booking;
use App\Models\vacation_summary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log

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
        $datePrefix = date('dmy');

        $latestTrip = DB::table('tour_booking')
            ->where('trip_id', 'like', "T2020{$datePrefix}%")
            ->orderByDesc('trip_id')
            ->first();
    
        $newDigits = $latestTrip
            ? str_pad((int) substr($latestTrip->trip_id, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '1001';
    
        $generatedTripId = "T2020{$datePrefix}{$newDigits}";
    
        return view('itinerary.itineraryform', [
            'generatedTripId' => $generatedTripId
        ]);
        // return view('itinerary.itineraryform');
    }

    public function store(Request $request)
    {
        // Validate the request
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
    
        Log::info('Form Data:', $request->all());
    
        // ✅ Generate Trip ID if not provided
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
        } else {
            $generatedTripId = $validated['tripId'];
        }
    
        // ✅ Handle file uploads
        $uploadedImages = [];
        if ($request->hasFile('timages')) {
            $uploadedImages['timages'] = $request->file('timages')->store('tour_images', 'public');
        }
        if ($request->hasFile('flightimages')) {
            $uploadedImages['flightimages'] = $request->file('flightimages')->store('flight_images', 'public');
        }
        if ($request->hasFile('officerimage')) {
            $uploadedImages['officerimage'] = $request->file('officerimage')->store('officer_image', 'public');
        }
    
        try {
            // ✅ Save to tour_booking table
            $tourBookingData = [
                'trip_id' => $generatedTripId,
                'username' => $validated['userName'],
                'tour_name' => $validated['tourName'],
                'check_in' => $validated['checkIn'],
                'check_out' => $validated['checkOut'],
                'adults' => $validated['numAdults'],
                'children' => $validated['numChildren'],
                'inclusion' => $validated['inclusion'],
                'exclusion' => $validated['exclusion'],
                'cost' => $validated['cost'],
                'tour_image' => $uploadedImages['timages'] ?? null,
                'notes' => $validated['notes'],
                'hotel' => $validated['hotel'],
                'flight' => $validated['flight'],
                'ftimage' => $uploadedImages['flightimages'] ?? null,
                'officerName' => $validated['officerName'],
                'officerimage' => $uploadedImages['officerimage'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
    
            $tourBooking = tour_booking::create($tourBookingData);
    
            // ✅ Save vacation_summary if available
            if ($request->stay && $request->date && $request->itinerary) {
                foreach ($request->stay as $index => $stay) {
                    $daily = new vacation_summary();
                    $daily->itinerary_id = $tourBooking->id;
                    $daily->stay = $stay;
                    $daily->date = $request->date[$index];
                    $daily->itinerary = $request->itinerary[$index];
    
                    if ($request->hasFile('images') && isset($request->file('images')[$index])) {
                        $daily->image = $request->file('images')[$index]->store('day_images', 'public');
                    }
    
                    $daily->save();
                }
            }
    
            // ✅ Redirect with success
            return redirect('/adminItinerary')->with('success', 'Itinerary saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving itinerary: ' . $e->getMessage());
            // return redirect()->back()->with('error', 'An error occurred while submitting the itinerary.');
        }
    }
    
}

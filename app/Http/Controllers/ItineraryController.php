<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tour_booking;
use App\Models\VacationSummary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Mpdf\Mpdf;
use Exception;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\JsonResponse;



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
            ->where('trip_id', 'like', "T20{$datePrefix}%")
            ->orderByDesc('trip_id')
            ->first();


        $newDigits = $latestTrip
            ? str_pad((int) substr($latestTrip->trip_id, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '1001';


        $generatedTripId = "T20{$datePrefix}{$newDigits}";

        return view('itinerary.itineraryform', [
            'generatedTripId' => $generatedTripId
        ]);
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
            'officerName' => 'required|string',
        ]);


        Log::info('Form Data:', $request->all());

    if (empty($validated['tripId'])) {
        $datePrefix = date('dmy');  

        $latestTrip = DB::table('tour_booking')
            ->where('trip_id', 'like', "T20{$datePrefix}%")  
            ->orderByDesc('trip_id')  
            ->first(); 

      
        $newDigits = $latestTrip
            ? str_pad((int) substr($latestTrip->trip_id, -4) + 1, 4, '0', STR_PAD_LEFT)  
            : '1001';  
        $generatedTripId = "T20{$datePrefix}{$newDigits}"; 
    } else {
       
        $generatedTripId = $validated['tripId'];
    }

     
          // ✅ Upload to Cloudinary
        $uploadedImages = [];
        if ($request->hasFile('tourImage')) {
            $uploaded = Cloudinary::upload($request->file('tourImage')->getRealPath(), ['folder' => 'Travels2020/tour_image']);
            $uploadedImages['tourImage'] = $uploaded->getSecurePath();
        }
    
        if ($request->hasFile('flightimage')) {
            $uploaded = Cloudinary::upload($request->file('flightimage')->getRealPath(), ['folder' => 'Travels2020/flight_images']);
            $uploadedImages['flightimage'] = $uploaded->getSecurePath();
        }
    
        if ($request->hasFile('officerimage')) {
            $uploaded = Cloudinary::upload($request->file('officerimage')->getRealPath(), ['folder' => 'Travels2020/officer_image']);
            $uploadedImages['officerimage'] = $uploaded->getSecurePath();
        }
    

        try {
            // ✅ Save to tour_booking table
            $tourBookingData = [
                'trip_id'       => $generatedTripId,
                'username'      => $validated['userName'],
                'tour_name'     => $validated['tourName'],
                'check_in'      => $validated['checkIn'],
                'check_out'     => $validated['checkOut'],
                'adults'        => $validated['numAdults'],
                'children'      => $validated['numChildren'] ?? 0,
                'inclusion'     => $validated['inclusion'] ?? '',
                'exclusion'     => $validated['exclusion'] ?? '',
                'cost'          => $validated['cost'] ?? '',
                'map_image'     => $uploadedImages['tourImage'] ?? '',
                'notes'         => $validated['notes'] ?? '',
                'hotel'         => $validated['hotel'] ?? '',
                'flight'        => $validated['flight'] ?? '',
                'flightimage'   => $uploadedImages['flightimage'] ?? '',
                'officerName'   => $validated['officerName'],
                'officerimage'  => $uploadedImages['officerimage'] ?? '',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            


            $tourBooking = tour_booking::create($tourBookingData);


            // ✅ Save vacation_summary if available


            if ($request->stay && $request->date && $request->itinerary_content) {
                foreach ($request->stay as $index => $stay) {
                    $daily = new VacationSummary();
                    $daily->fk_tour_booking = $tourBooking->id; // ✅ foreign key assignment
                    $daily->stay = $stay;
                    $daily->date = $request->date[$index];
                    $daily->itinerary_content = $request->itinerary_content[$index];

                    // ✅ Image handling per index
                    if ($request->hasFile("images.{$index}")) {
                        $imgUpload = Cloudinary::upload($request->file("images.{$index}")->getRealPath(), [
                            'folder' => 'Travels2020/day_images'
                        ]);
                        $daily->image = $imgUpload->getSecurePath();
                    }

                    $daily->save();
                }
            }


             return response()->json(['status' => 'success', 'id' => $tourBooking->id]);
        } catch (\Exception $e) {
            Log::error('Error saving itinerary: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function singlepdf($id)
    {
        try {
            if (!is_numeric($id)) {
                return response("Invalid Tour ID.", 400);
            }

            $row = DB::table('tour_booking as tb')
                ->leftJoin('vacation_summary as vs', 'vs.fk_tour_booking', '=', 'tb.id')
                ->where('tb.id', $id)
                ->select('tb.*', 'vs.*')
                ->first();

            if (!$row) {
                throw new Exception("No records found for Tour ID: " . $id);
            }

            $checkInDate = new \DateTime($row->check_in);
            $checkOutDate = new \DateTime($row->check_out);
            $duration = $checkInDate->diff($checkOutDate)->days + 1;
            $nights = $duration - 1;

            $whatsappLink = "https://wa.me/919445552020?text=" . urlencode("Hello, I would like to know more information about our tour {$row->tour_name} trip Id: {$row->trip_id}");
            $razorpayLink = "https://pages.razorpay.com/travels2020";

            $vacationResults = DB::table('vacation_summary')->where('fk_tour_booking', $id)->get();


            $html = view('itinerary.singlepdf', [
                'row' => $row,
                'vacationResults' => $vacationResults,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
                'duration' => $duration,
                'nights' => $nights,
                'whatsappLink' => $whatsappLink,
                'razorpayLink' => $razorpayLink
            ])->render();

            $mpdf = new Mpdf([
                'format' => [193.5, 3800],
                'margin_top' => 10,
                'margin_bottom' => 15,
                'margin_left' => 15,
                'margin_right' => 15,
            ]);
            $mpdf->SetAutoPageBreak(false);
            $mpdf->WriteHTML($html);

            $filename = "Mr. {$row->username}-{$row->tour_name} ({$nights} Nights / {$duration} Days) Package.pdf";

            return response($mpdf->Output($filename, 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");

        } catch (Exception $e) {
            return response("Error: " . $e->getMessage(), 500);
        }
    }

    public function multiplepdf($id)
    {
        try {
            if (!is_numeric($id)) {
                return response("Invalid Tour ID.", 400);
            }
    
            $booking = DB::table('tour_booking as tb')
                ->where('tb.id', $id)
                ->first();
    
            if (!$booking) {
                throw new \Exception("No booking found for Tour ID: $id");
            }
    
            $vacationSummaries = DB::table('vacation_summary')
                ->where('fk_tour_booking', $id)
                ->get();
    
            if ($vacationSummaries->isEmpty()) {
                throw new \Exception("No itinerary data found.");
            }
    
            $checkInDate = new \DateTime($booking->check_in);
            $checkOutDate = new \DateTime($booking->check_out);
            $duration = $checkInDate->diff($checkOutDate)->days + 1;
            $nights = $duration - 1;
    
            $html = view('itinerary.multiplepdf', [
                'row' => $booking,
                'vacationResults' => $vacationSummaries,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
                'duration' => $duration,
                'nights' => $nights,
                'razorpayLink' => "https://pages.razorpay.com/travels2020",
            ])->render();
    
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 40,
                'margin_bottom' => 40,
                'margin_left' => 15,
                'margin_right' => 15
            ]);
    
          // Set header and footer globally with clickable image links
            $mpdf->SetHTMLHeader('
            <div style="text-align: center;">
                <a href="http://www.travels2020.com" target="_blank">
                    <img src="' . public_path('images/Header.jpg') . '" width="100%" />
                </a>
            </div>
            ');

            $mpdf->SetHTMLFooter('
            <div style="text-align: center;">
                <a href="https://www.google.com/maps/place/Travels2020/@12.9901923,80.2539563,17z/data=!3m1!4b1!4m6!3m5!1s0x3a525d799e2de9e9:0xb9c456c8c7ba873d!8m2!3d12.9901923!4d80.2539563!16s%2Fg%2F11bcdznhzc?entry=ttu" target="_blank">
                    <img src="' . public_path('images/Footer.jpg') . '" width="100%" />
                </a>
            </div>
            ');

            // Add page content
            $mpdf->WriteHTML($html);
    
            $filename = "Mr. {$booking->username}-{$booking->tour_name} ({$nights} Nights / {$duration} Days) Package.pdf";
    
            return response($mpdf->Output($filename, 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    
        } catch (\Exception $e) {
            return response("Error: " . $e->getMessage(), 500);
        }
    }
    

    public function listitineray()
    {
        $trips = tour_booking::all(); // or paginate if needed
        return response()->json($trips);
    }

    // public function delete(Request $request)
    // {
    //     $tripId = $request->trip_id;
    //     $deleted = tour_booking::where('id', $tripId)->delete();

    //     if ($deleted) {
    //         return response()->json(['success' => true, 'message' => 'Trip deleted successfully.']);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'Trip not found.']);
    //     }
    // }

    // public function edit(int $id)
    // {
      
    //     $trip = DB::table('tour_booking')->where('id', $id)->first();
    
    //     if (!$trip) {
    //         return response()->json(['success' => false, 'message' => 'Trip not found.'], 404);
    //     }
    
       
    //     $vacationSummary = DB::table('vacation_summary')
    //         ->where('fk_tour_booking', $id)
    //         ->get();
    
    //     return response()->json([
    //         'success' => true,
    //         'trip' => $trip,
    //         'vacation_summary' => $vacationSummary
    //     ]);
    // }
    
    
    public function edit(int $id)
    {
        // Fetch the main trip details
        $trip = DB::table('tour_booking')->where('id', $id)->first();
    
        if (!$trip) {
            abort(404, 'Trip not found');
        }
    
        // Fetch related vacation summary entries
        $vacationSummary = DB::table('vacation_summary')
            ->where('fk_tour_booking', $id)
            ->get();
    
        // Return the Blade view with data
        return  view('itinerary.itineraryform', [
            'trip' => $trip,
            'vacation_summary' => $vacationSummary,
            'generatedTripId' => $trip->trip_id
        ]);
    }
    

public function update(Request $request, $id)
{
    try {
        DB::table('tour_booking')->where('id', $id)->update([
            'trip_id' => $request->tripId,
            'username' => $request->userName,
            'tour_name' => $request->tourName,
            'check_in' => $request->checkIn,
            'check_out' => $request->checkOut,
            'adults' => $request->numAdults,
            'children' => $request->numChildren,
            'inclusion' => $request->inclusion,
            'exclusion' => $request->exclusion,
            'notes' => $request->notes,
            'cost' => $request->cost,
            'hotel' => $request->hotel,
            'flight' => $request->flight,
        ]);

        // Handle file uploads
        if ($request->hasFile('tourImages')) {
            $path = $request->file('tourImages')->store('public/tour_images');
            DB::table('tour_booking')->where('id', $id)->update(['tour_image' => $path]);
        }

        if ($request->hasFile('flightimages')) {
            $path = $request->file('flightimages')->store('public/flight_images');
            DB::table('tour_booking')->where('id', $id)->update(['ftimage' => $path]);
        }

        // Update vacation_summary
        DB::table('vacation_summary')->where('fk_tour_booking', $id)->delete(); // Clear old

        foreach ($request->days as $day) {
            $imagePath = null;
            if (isset($day['vsImages'])) {
                $imagePath = $day['vsImages']->store('public/vacation_images');
            }

            DB::table('vacation_summary')->insert([
                'fk_tour_booking' => $id,
                'stay' => $day['stay'],
                'date' => $day['date'],
                'itinerary_content' => $day['itinerary'],
                'image' => $imagePath,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Trip updated successfully.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


}

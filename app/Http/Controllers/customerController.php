<?php

namespace App\Http\Controllers;

use App\Models\CustomersDetails;
use App\Models\PassengerDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Validator;

class CustomerController extends Controller
{
    public function customerform()
    {
        return view('customers.customerform');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'travelFrom' => 'required|string|max:255',
            'travelTo' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:255',
            'numAdults' => 'required|integer|min:0',
            'numChildren' => 'nullable|integer|min:0',
        ]);
    
        try {
            // Save customer details
            $customer = CustomerDetails::create([
                'travel_from' => $validatedData['travelFrom'],
                'travel_to' => $validatedData['travelTo'],
                'destination' => $validatedData['destination'],
                'relationship' => $validatedData['relationship'] ?? null,
                'adults' => $validatedData['numAdults'],
                'children' => $validatedData['numChildren'] ?? 0,
            ]);
    
            // Handle passenger details
            $passengers = $request->input('passengers', []);
            foreach ($passengers as $index => $passenger) {
                $passportFront = $request->file("passengers.$index.passportFront");
                $passportBack = $request->file("passengers.$index.passportBack");
                $panCard = $request->file("passengers.$index.panCard");
    
                PassengerDetails::create([
                    'customer_id' => $customer->id,
                    'first_name' => $passenger['passengerFirstName'] ?? null,
                    'last_name' => $passenger['passengerLastName'] ?? null,
                    'mobile_number' => $passenger['mobileNumber'] ?? null,
                    'email' => $passenger['email'] ?? null,
                    'gender' => $passenger['gender'] ?? null,
                    'dob' => $passenger['dob'] ?? null,
                    'anniversary' => $passenger['anniversary'] ?? null,
                    'pan_number' => $passenger['panNumber'] ?? null,
                    'passport_number' => $passenger['passportNumber'] ?? null,
                    'passport_issue_city' => $passenger['passportIssueCity'] ?? null,
                    'passport_issue_country' => $passenger['passportIssueCountry'] ?? null,
                    'passport_issue_date' => $passenger['passportIssueDate'] ?? null,
                    'passport_expiry_date' => $passenger['passportExpiryDate'] ?? null,
                    'passport_front' => $passportFront ? Cloudinary::upload($passportFront->getRealPath())->getSecurePath() : null,
                    'passport_back' => $passportBack ? Cloudinary::upload($passportBack->getRealPath())->getSecurePath() : null,
                    'pan_card' => $panCard ? Cloudinary::upload($panCard->getRealPath())->getSecurePath() : null,
                ]);
            }
    
            return response()->json(['success' => true, 'message' => 'Form submitted successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    

}

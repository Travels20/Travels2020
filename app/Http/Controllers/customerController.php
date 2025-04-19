<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetails;
use App\Models\PassengerDetails;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customerform()
    {
        return view('customers.customerform'); 
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'travel_from' => 'required|string|max:255',
            'travel_to' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:255',  // Assuming relationship is optional
            'adults' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
            // Validate passenger details if provided
            'passengers' => 'nullable|array',
            'passengers.*.first_name' => 'required|string|max:255',
            'passengers.*.last_name' => 'required|string|max:255',
            'passengers.*.mobile_number' => 'required|string|max:255',
            'passengers.*.email' => 'required|email|max:255',
            // Add other passenger fields here as needed
        ]);

        try {
            // Create a new customer record in the database
            $customer = CustomerDetails::create([
                'travel_from' => $validatedData['travel_from'],
                'travel_to' => $validatedData['travel_to'],
                'destination' => $validatedData['destination'],
                'relationship' => $validatedData['relationship'] ?? null,
                'adults' => $validatedData['adults'],
                'children' => $validatedData['children'],
            ]);

            // If passengers data is provided, store the passenger details
            if (isset($validatedData['passengers']) && count($validatedData['passengers']) > 0) {
                foreach ($validatedData['passengers'] as $passengerData) {
                    PassengerDetails::create([
                        'customer_id' => $customer->id,
                        'first_name' => $passengerData['first_name'],
                        'last_name' => $passengerData['last_name'],
                        'mobile_number' => $passengerData['mobile_number'],
                        'email' => $passengerData['email'],
                        'gender' => $passengerData['gender'] ?? null, // Assuming optional
                        'dob' => $passengerData['dob'] ?? null, // Assuming optional
                        'anniversary' => $passengerData['anniversary'] ?? null, // Assuming optional
                        'pan_number' => $passengerData['pan_number'] ?? null, // Assuming optional
                        'passport_number' => $passengerData['passport_number'] ?? null, // Assuming optional
                        'passport_issue_city' => $passengerData['passport_issue_city'] ?? null, // Assuming optional
                        'passport_issue_country' => $passengerData['passport_issue_country'] ?? null, // Assuming optional
                        'passport_issue_date' => $passengerData['passport_issue_date'] ?? null, // Assuming optional
                        'passport_expiry_date' => $passengerData['passport_expiry_date'] ?? null, // Assuming optional
                        'passport_front' => $passengerData['passport_front'] ?? null, // Assuming optional
                        'passport_back' => $passengerData['passport_back'] ?? null, // Assuming optional
                        'pan_card' => $passengerData['pan_card'] ?? null, // Assuming optional
                    ]);
                }
            }

            // Redirect with success
            return redirect('/adminItinerary')->with('success', 'Itinerary saved successfully!');
            
        } catch (\Exception $e) {
            // Handle any errors during saving
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while saving customer or passenger details: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function invoiceForm()
    {
        return view('invoice.invoiceForm'); 
    }

    public function store(Request $request)
    {
        // ✅ Validate all required fields
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string|max:255',
            'customer_gst_no' => 'nullable|string|max:50',
            'travelFrom' => 'required|date|before_or_equal:travelTo', // Ensure travelFrom is before or equal to travelTo
            'travelTo' => 'required|date|after_or_equal:travelFrom', // Ensure travelTo is after or equal to travelFrom
            'destination' => 'required|string',
            'destinationOthers' => 'nullable|string',
            'numAdults' => 'required|integer|min:1',
            'numChildren' => 'required|integer|min:0',
            'adults_cost' => 'required|numeric|min:0',
            'child_cost' => 'required|numeric|min:0',
            'service_cost' => 'required|numeric|min:0',
            'service_gst' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'office_gst_no' => 'nullable|string|max:50',
            'office_pan_no' => 'nullable|string|max:50',
        ]);
    
        // ✅ Handle 'Others' option for destination
        $destination = $validated['destination'] === 'Others'
            ? $request->input('destinationOthers')
            : $validated['destination'];
    
        // ✅ Create Invoice
        $invoice = Invoice::create([
            'customer_name' => $validated['customer_name'],
            'customer_address' => $validated['customer_address'],
            'customer_gst_no' => $validated['customer_gst_no'] ?? null,  // Ensure null for optional fields
            'travel_from' => $validated['travelFrom'],
            'travel_to' => $validated['travelTo'],
            'destination' => $destination,
            'num_adults' => $validated['numAdults'],
            'num_children' => $validated['numChildren'],
            'adults_cost' => $validated['adults_cost'],
            'child_cost' => $validated['child_cost'],
            'service_cost' => $validated['service_cost'],
            'service_gst' => $validated['service_gst'],
            'notes' => $validated['notes'] ?? null,  // Ensure null for optional fields
            'office_gst_no' => $validated['office_gst_no'] ?? null,
            'office_pan_no' => $validated['office_pan_no'] ?? null,
        ]);
    
        // ✅ Return JSON response with invoice ID
        return response()->json(['invoice_id' => $invoice->id]);
    }

    public function generatePDF($id)
    {
        try {
            // Fetch the invoice from the database
            $invoice = Invoice::find($id);
    
            // Check if the invoice exists
            if (!$invoice) {
                return response('Invoice not found', 404);
            }
    
            // Calculate duration and nights
            $checkIn = Carbon::parse($invoice->travel_from);
            $checkOut = Carbon::parse($invoice->travel_to);
            $duration = $checkIn->diffInDays($checkOut) + 1; // Total duration
            $nights = $duration - 1; // Nights is 1 less than duration
    
            // Calculate total costs
            $adultsTotalCost = $invoice->adults_cost * $invoice->num_adults;
            $childTotalCost = $invoice->child_cost * $invoice->num_children;
            $serviceCost = $invoice->service_cost;
            $gstRate = $invoice->service_gst;
            $gstAmount = ($serviceCost * $gstRate) / 100;
            $totalAmount = $adultsTotalCost + $childTotalCost + $serviceCost + $gstAmount;
    
            // Prepare header and footer images (ensure these files exist in public/images)
            $header = '<img src="' . public_path('images/Header.jpg') . '" width="100%">';
            $footer = '<img src="' . public_path('images/Footer.jpg') . '" width="100%" style="margin-top:20px;">';
    
            // Configure mPDF
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
            ]);
    
            // Set header and footer
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
    
            // Generate HTML content using the Blade view
            $html = view('pdf.invoice', compact(
                'invoice',
                'nights',
                'duration',
                'adultsTotalCost',
                'childTotalCost',
                'serviceCost',
                'gstAmount',
                'totalAmount'
            ))->render();
    
            // Write the HTML content to the PDF
            $mpdf->WriteHTML($html);
    
            // Generate dynamic filename
            $filename = "Mr. {$invoice->customer_name} - {$invoice->destination} ({$nights} Nights / {$duration} Days) Package.pdf";
    
            // Return the generated PDF as a response for download
            return response($mpdf->Output('', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    
        } catch (\Exception $e) {
            // Log the error and return a response
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return response('Error generating PDF: ' . $e->getMessage(), 500);
        }
    }


public function listInvoices() {
    $invoices = Invoice::all(); // Fetch invoices from the database
    return response()->json($invoices); // Return as JSON
}

public function index() {
    $invoices = Invoice::all(); // Or use paginate() if you want to paginate the results
    return view('invoice.index', compact('invoices'));
}

    
    
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Exception;

class InvoiceController extends Controller
{
    // Display the form for creating a new invoice
    public function invoiceForm()
    {
        return view('invoice.invoiceForm');
    }

    // Store a newly created invoice in the database
    public function store(Request $request)
    {
        try {
            // Validation rules for storing invoice data
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_address' => 'required|string|max:255',
                'customer_gst_no' => 'nullable|string|max:50',
                'travelFrom' => 'required|date|before_or_equal:travelTo',
                'travelTo' => 'required|date|after_or_equal:travelFrom',
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

            // Handling the 'Others' option for destination
            $destination = $validated['destination'] === 'Others' 
                ? $validated['destinationOthers'] 
                : $validated['destination'];

            // Create the invoice
            $invoice = Invoice::create([
                'customer_name' => $validated['customer_name'],
                'customer_address' => $validated['customer_address'],
                'customer_gst_no' => $validated['customer_gst_no'] ?? null,
                'travel_from' => $validated['travelFrom'],
                'travel_to' => $validated['travelTo'],
                'destination' => $destination,
                'num_adults' => $validated['numAdults'],
                'num_children' => $validated['numChildren'],
                'adults_cost' => $validated['adults_cost'],
                'child_cost' => $validated['child_cost'],
                'service_cost' => $validated['service_cost'],
                'service_gst' => $validated['service_gst'],
                'notes' => $validated['notes'] ?? null,
                'office_gst_no' => $validated['office_gst_no'] ?? null,
                'office_pan_no' => $validated['office_pan_no'] ?? null,
            ]);

            // Return success response
            return response()->json(['status' => 'success', 'id' => $invoice->id]);
        } catch (Exception $e) {
            // Log the error and return error response
            Log::error('Error saving invoice: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Generate PDF for the invoice
    public function generatePDF($id)
    {
        try {
            // Find the invoice
            $invoice = Invoice::find($id);

            if (!$invoice) {
                return response('Invoice not found', 404);
            }

            // Calculate date difference
            $checkIn = Carbon::parse($invoice->travel_from);
            $checkOut = Carbon::parse($invoice->travel_to);
            $duration = $checkIn->diffInDays($checkOut) + 1; // Include both start and end date
            $nights = $duration - 1; // Total nights are the duration minus 1

            // Calculate total costs
            $adultsTotalCost = $invoice->adults_cost * $invoice->num_adults;
            $childTotalCost = $invoice->child_cost * $invoice->num_children;
            $serviceCost = $invoice->service_cost;
            $gstRate = $invoice->service_gst;
            $gstAmount = ($serviceCost * $gstRate) / 100;
            $totalAmount = $adultsTotalCost + $childTotalCost + $serviceCost + $gstAmount;

            // Set PDF header and footer
            $header = '<img src="' . public_path('images/Header.jpg') . '" width="100%">';
            $footer = '<img src="' . public_path('images/Footer.jpg') . '" width="100%" style="margin-top:20px;">';

            // Initialize mPDF instance
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
            ]);

            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            // Load the HTML view for the PDF
            $html = view('invoice.invoicepdf', compact(
                'invoice',
                'nights',
                'duration',
                'adultsTotalCost',
                'childTotalCost',
                'serviceCost',
                'gstAmount',
                'totalAmount'
            ))->render();

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Generate a safe filename for the PDF
            $safeName = preg_replace('/[^A-Za-z0-9\- ]/', '', $invoice->customer_name);
            $safeDestination = preg_replace('/[^A-Za-z0-9\- ]/', '', $invoice->destination);
            $filename = "Mr. {$safeName} - {$safeDestination} ({$nights} Nights {$duration} Days) Package.pdf";

            // Output the PDF file
            return response($mpdf->Output('', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return response('Error generating PDF: ' . $e->getMessage(), 500);
        }
    }

    // Return all invoices as JSON for DataTables or AJAX
    public function listInvoices()
    {
        try {
            $invoices = Invoice::all();
            return response()->json($invoices);
        } catch (Exception $e) {
            Log::error('Error fetching invoices: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Carbon\Carbon; // ✅ Add this, since you're using Carbon
use Exception;

class InvoiceController extends Controller
{
    // Show the invoice creation form
    public function invoiceForm()
    {
        return view('invoice.invoiceForm');
    }

    // Store the invoice data
    public function store(Request $request)
    {
        // ✅ Validate all required fields
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

        // ✅ Handle 'Others' option for destination
        $destination = $validated['destination'] === 'Others'
            ? $validated['destinationOthers']
            : $validated['destination'];

        // ✅ Create Invoice
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

        return response()->json(['invoice_id' => $invoice->id]);
    }

    // Generate the invoice PDF
    public function generatePDF($id)
    {
        try {
            $invoice = Invoice::find($id);

            if (!$invoice) {
                return response('Invoice not found', 404);
            }

            $checkIn = Carbon::parse($invoice->travel_from);
            $checkOut = Carbon::parse($invoice->travel_to);
            $duration = $checkIn->diffInDays($checkOut) + 1;
            $nights = $duration - 1;

            $adultsTotalCost = $invoice->adults_cost * $invoice->num_adults;
            $childTotalCost = $invoice->child_cost * $invoice->num_children;
            $serviceCost = $invoice->service_cost;
            $gstRate = $invoice->service_gst;
            $gstAmount = ($serviceCost * $gstRate) / 100;
            $totalAmount = $adultsTotalCost + $childTotalCost + $serviceCost + $gstAmount;

            $header = '<img src="' . public_path('images/Header.jpg') . '" width="100%">';
            $footer = '<img src="' . public_path('images/Footer.jpg') . '" width="100%" style="margin-top:20px;">';

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

            $mpdf->WriteHTML($html);

            $safeName = preg_replace('/[^A-Za-z0-9\- ]/', '', $invoice->customer_name);
            $safeDestination = preg_replace('/[^A-Za-z0-9\- ]/', '', $invoice->destination);
            $filename = "Mr. {$safeName} - {$safeDestination} ({$nights} Nights {$duration} Days) Package.pdf";

            return response($mpdf->Output('', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');

        } catch (Exception $e) {
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return response('Error generating PDF: ' . $e->getMessage(), 500);
        }
    }

    // Return all invoices as JSON (for DataTables, etc.)
    public function listInvoices()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    // Show the invoice list page
    public function index()
    {
        $invoices = Invoice::all(); // Can be replaced with paginate() if needed
        return view('invoice.index', compact('invoices'));
    }
}

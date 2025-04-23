<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Mpdf\Mpdf;
use Exception;

class InvoiceController extends Controller
{
    // Show invoice form
    public function invoiceForm()
    {
        return view('invoice.invoiceForm');
    }

    // Store invoice
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_name'     => 'required|string|max:255',
                'customer_address'  => 'required|string|max:255',
                'customer_gst_no'   => 'nullable|string|max:50',
                'travelFrom'        => 'required|date|before_or_equal:travelTo',
                'travelTo'          => 'required|date|after_or_equal:travelFrom',
                'destination'       => 'required|string',
                'destinationOthers' => 'nullable|string',
                'numAdults'         => 'required|integer|min:1',
                'numChildren'       => 'required|integer|min:0',
                'adults_cost'       => 'required|numeric|min:0',
                'child_cost'        => 'required|numeric|min:0',
                'service_cost'      => 'required|numeric|min:0',
                'service_gst'       => 'required|numeric|min:0',
                'notes'             => 'nullable|string',
                'office_gst_no'     => 'nullable|string|max:50',
                'office_pan_no'     => 'nullable|string|max:50',
            ]);

            $destination = $validated['destination'] === 'Others'
                ? $validated['destinationOthers']
                : $validated['destination'];

            $invoice = Invoice::create([
                'customer_name'    => $validated['customer_name'],
                'customer_address' => $validated['customer_address'],
                'customer_gst_no'  => $validated['customer_gst_no'] ?? null,
                'travel_from'      => $validated['travelFrom'],
                'travel_to'        => $validated['travelTo'],
                'destination'      => $destination,
                'num_adults'       => $validated['numAdults'],
                'num_children'     => $validated['numChildren'],
                'adults_cost'      => $validated['adults_cost'],
                'child_cost'       => $validated['child_cost'],
                'service_cost'     => $validated['service_cost'],
                'service_gst'      => $validated['service_gst'],
                'notes'            => $validated['notes'] ?? null,
                'office_gst_no'    => $validated['office_gst_no'] ?? null,
                'office_pan_no'    => $validated['office_pan_no'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'id'     => $invoice->id,
            ]);
        } catch (Exception $e) {
            Log::error('Invoice save failed: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to save invoice.',
            ], 500);
        }
    }

    // Generate invoice PDF and return as downloadable response
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
            $gstAmount = ($serviceCost * $invoice->service_gst) / 100;
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

            $mpdf->WriteHTML($html);

            $filename = "Mr. {$invoice->customer_name}-{$invoice->destination} ({$nights} Nights / {$duration} Days) Package.pdf";


            return response($mpdf->Output('', 'S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (Exception $e) {
            Log::error('Invoice PDF generation failed: ' . $e->getMessage());
            return response('PDF generation failed', 500);
        }
    }


    public function listInvoices()
{
    try {
        $invoices = Invoice::all();
        return response()->json($invoices);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch invoices'], 500);
    }
}

    
}

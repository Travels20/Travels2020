<?php

// app/Http/Controllers/ItineraryPdfController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\DB;
use Exception;

class ItineraryPdfController extends Controller
{
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

            // Load related vacation_summary entries
            $vacationResults = DB::table('vacation_summary')->where('fk_tour_booking', $id)->get();

            $html = view('pdfs.itinerary', [
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
                'format' => [193.5, 1800],
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
}


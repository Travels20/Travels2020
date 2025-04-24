<!DOCTYPE html>
<html>

<head>
    <title>Itinerary PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
        }

        h1 {
            text-align: center;
            font-size: 20pt;
            margin-bottom: 5px;
            font-weight: bold;
            color: rgb(11, 66, 105);
        }

        span {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 10px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #000;
        }

        .header {
            background-color: #A4B5C1;
            color: #fff;
            font-weight: bold;
        }

        .theaders {
            background-color: rgb(237, 241, 242);
            color: #000;
            font-weight: bold;
        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            border-radius: 10px;
            background-color: #f5f5f5;
            page-break-inside: avoid;
        }

        p {
            margin: 10px 0;
        }

        h2 {
            page-break-inside: avoid;
            text-align: left;
            font-size: 16pt;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <a href="http://www.travels2020.com" target="_blank"><img src="{{ public_path('images/Header.jpg') }}" width="100%"></a>

    <table style="margin-top: 10px; border: none;">
        <tr>
            <td style="font-weight: bold; font-size: 15pt; text-align: left; border: none;">Hey, {{ $row->username }}
            </td>
            <td style="text-align: right; border: none;"><strong>Generated On:</strong> {{ date('d-m-Y') }}</td>
        </tr>
    </table>

    <h1>{{ strtoupper($row->tour_name) }} <br><span> ({{ $nights }} Nights / {{ $duration }} Days)</span>
    </h1>

    @php
        $tourImage = $row->tour_image;
        if (pathinfo($tourImage, PATHINFO_EXTENSION) == 'png') {
            $tourImage = str_replace('.png', '.jpg', $tourImage); 
        }
    @endphp
  
    <img src="{{ $row->tour_image }}" width="100%" height="50%">

    <div class="box">
        <table>
            <tr class="theaders">
                <td><strong>TRIP ID:</strong></td>
                <td>{{ $row->trip_id }}</td>
            </tr>
            <tr>
                <td><strong>NO. OF PASSENGER:</strong></td>
                <td>{{ $row->adults }} adults, {{ $row->children }} {{ $row->children == 1 ? 'child' : 'children' }}
                </td>
            </tr>
            <tr class="theaders">
                <td><strong>DEPARTURE:</strong></td>
                <td>{{ \Carbon\Carbon::parse($row->check_in)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>ARRIVAL:</strong></td>
                <td>{{ \Carbon\Carbon::parse($row->check_out)->format('d-m-Y') }}</td>
            </tr>
        </table>
    </div>

    <h2>Itinerary:</h2>

    @foreach ($vacationResults as $index => $day)
        <div class="box">
            <table>
                <tr class="header">
                    <td>
                        <h3>Day {{ $index + 1 }}</h3>
                    </td>
                    <td>{{ $day->stay }}</td>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td>
                    <img src="{{ $day->image }}" style="width: 100%; max-width: 200px; border-radius: 5px;">
                   </td>
                    <td colspan="2">{!! $day->itinerary_content !!}</td>
                </tr>
            </table>
        </div>
    @endforeach

    @if (!empty($row->cost))
        <div>
            <h2>LAND PACKAGE COST:</h2>{!! $row->cost !!}
        </div>
    @endif

    @if (!empty($row->hotel))
        <h2>Hotel Details:</h2>{!! $row->hotel !!}
    @endif

    @if (!empty($row->flight))
        <h2>Flight Details:</h2>{!! $row->flight !!}
        <!-- @if (!empty($row->flightimage))
            <img src="{{ public_path($row->flightimage) }}" width="100%" height="auto"><br><br>
        @endif -->
        @if (!empty($row->ftimage))
            <img src="{{ $row->ftimage }}" width="100%" height="auto"><br><br>
        @endif

    @endif

    @if (!empty($row->inclusion))
        <h2>Inclusion:</h2>{!! $row->inclusion !!}
    @endif

    @if (!empty($row->exclusion))
        <h2>Exclusion:</h2>{!! $row->exclusion !!}
    @endif

    @if (!empty($row->notes))
        <h2>Important Notes:</h2>{!! $row->notes !!}
    @endif

    <div class="box">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><img  src="{{ $row->officerimage ? $row->officerimage : asset('images/pothy.jpg') }}"  width="100"
                        height="100" style="border-radius: 50%;"></td>
                <td style="text-align: center; border: none;">
                    <p>Your trip is planned by travels2020<br> {{ $row->officerName }}<br><span>1500+ trips planned</span></p>
                    <p>For any queries or requests?</p>
                </td>
                <td style="text-align: right; border: none;">
                    <p>Say Hello<br>
                        <a href="https://wa.me/919445552020?text={{ urlencode('Hello, I would like to know more about tour ' . $row->tour_name . ' (Trip ID: ' . $row->trip_id . ')') }}"
                            style="text-decoration: none; font-weight: bold; color: green;">
                            WhatsApp
                        </a>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="box">
        <table style="border: none;">
            <tr>
                <td style="border: none;">
                    <h2>Bank Details</h2>
                    <p><strong>Bank:</strong> HDFC</p>
                    <p><strong>Account:</strong> Arctictern Consultancy Services Pvt Ltd</p>
                    <p><strong>Account No.:</strong> 50200044220791</p>
                    <p><strong>Branch:</strong> Adambakkam</p>
                    <p><strong>IFSC:</strong> HDFC0001858</p>
                </td>
                <td style="text-align: right; border: none;">
                    <p>Click here to <br>Pay Online</p>
                    <a href="https://pages.razorpay.com/travels2020">
                        <img src="{{ public_path('images/Razorpayimage.png') }}" width="100">
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <a href="https://www.google.com/maps/place/Travels2020/@12.9901923,80.2539563,17z/data=!3m1!4b1!4m6!3m5!1s0x3a525d799e2de9e9:0xb9c456c8c7ba873d!8m2!3d12.9901923!4d80.2539563!16s%2Fg%2F11bcdznhzc?entry=ttu&g_ep=EgoyMDI1MDIwMy4wIKXMDSoASAFQAw%3D%3D" target="_blank">
  <img src="{{ public_path('images/Footer.jpg') }}" width="100%"
            style="margin-top: 40px;"></a>

</body>

</html>

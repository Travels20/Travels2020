<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 15px;
            border-radius: 8px;
            background-color: #f5f5f5;
            page-break-inside: avoid;
        }

        h1, h3 {
            color: #0B4269;
            margin-top: 10px;
        }

        h2 {
            text-align: left;
            font-size: 16pt;
            margin-top: 5px;
            page-break-after: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #000;
        }

        .theaders {
            background-color: #eef2f5;
        }

        .itinerary-img {
            width: 100%;
            max-width: 200px;
            border-radius: 5px;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>
<body>

<table class="no-border">
    <tr>
        <td style="font-weight: bold; font-size: 15pt; text-align: left;" class="no-border">
            Hey, {{ $row->username }}
        </td>
        <td style="text-align: right;" class="no-border">
            <strong>Generated On:</strong> {{ date('d-m-Y') }}
        </td>
    </tr>
</table>

<h1 style="text-align:center;">{{ strtoupper($row->tour_name) }}</h1>
<p style="text-align:center;">({{ $nights }} Nights / {{ $duration }} Days)</p>

@if ($row->tour_image)
    <img src="{{ $row->tour_image }}" width="100%" height="50%" style="margin-top: 10px;" />
@endif

<div class="box">
    <table>
        <tr class="theaders">
            <td><strong>TRIP ID:</strong></td>
            <td>{{ $row->trip_id }}</td>
        </tr>
        <tr>
            <td><strong>NO. OF PASSENGER:</strong></td>
            <td>{{ $row->adults }} adults, {{ $row->children }} {{ $row->children == 1 ? 'child' : 'children' }}</td>
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

<pagebreak />
<h2>Itinerary:</h2>

@foreach ($vacationResults as $index => $day)
    <div class="box">
        <table>
            <tr class="theaders">
                <td><h3>Day {{ $index + 1 }}</h3></td>
                <td>{{ $day->stay }}</td>
                <td>{{ \Carbon\Carbon::parse($day->date)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>
                    @if ($day->image)
                        <img src="{{ $day->image }}" class="itinerary-img">
                    @endif
                </td>
                <td colspan="2">{!! $day->itinerary_content !!}</td>
            </tr>
        </table>
    </div>
@endforeach

@if (!empty($row->cost))
        <h2>LAND PACKAGE COST:</h2>
        {!! $row->cost !!}
@endif

@if (!empty($row->hotel))
    <h2>Hotel Details:</h2>
    {!! $row->hotel !!}
@endif

@if (!empty($row->flight))
    <h2>Flight Details:</h2>
    {!! $row->flight !!}
    @if (!empty($row->ftimage))
        <img src="{{ $row->ftimage }}" width="100%" height="auto"><br><br>
    @endif
@endif

@if (!empty($row->inclusion))
    <h2>Inclusion:</h2>
    {!! $row->inclusion !!}
@endif

@if (!empty($row->exclusion))
    <h2>Exclusion:</h2>
    {!! $row->exclusion !!}
@endif

@if (!empty($row->notes))
    <h2>Important Notes:</h2>
    {!! $row->notes !!}
@endif

<div class="box">
    <table class="no-border">
        <tr>
            <td class="no-border">
                <img src="{{ $row->officerimage ? $row->officerimage : asset('images/pothy.jpg') }}" width="100" height="100" style="border-radius: 50%;">
            </td>
            <td style="text-align: center;" class="no-border">
                <p>Your trip is planned by travels2020<br>{{ $row->officerName }}<br><span>1500+ trips planned</span></p>
                <p>For any queries or requests?</p>
            </td>
            <td style="text-align: right;" class="no-border">
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
    <table class="no-border">
        <tr>
            <td class="no-border">
                <h2>Bank Details</h2>
                <p><strong>Bank:</strong> HDFC</p>
                <p><strong>Account:</strong> Arctictern Consultancy Services Pvt Ltd</p>
                <p><strong>Account No.:</strong> 50200044220791</p>
                <p><strong>Branch:</strong> Adambakkam</p>
                <p><strong>IFSC:</strong> HDFC0001858</p>
            </td>
            <td style="text-align: right;" class="no-border">
                <p>Click here to <br>Pay Online</p>
                <a href="{{ $razorpayLink }}">
                    <img src="{{ public_path('images/Razorpayimage.png') }}" width="100">
                </a>
            </td>
        </tr>
    </table>
</div>

</body>
</html>

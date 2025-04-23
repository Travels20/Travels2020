<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 10px 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 8px 10px;
            border: 1px solid #000;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: left;
        }

        .no-border td {
            border: none;

            padding: 4px 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .section {
            margin-top: 25px;
        }

        .signature-img {
            margin: 10px 0;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    {{-- Invoice Header --}}
    <div class="text-right" style="margin-top: 50px;">
        <p><span class="bold">Invoice No:</span> TR2020{{ $invoice->id }}</p>
        <p><span class="bold">Invoice Date:</span> {{ now()->format('d-m-Y') }}</p>
    </div>

    {{-- Seller & Buyer Info --}}
    <table class="no-border" style="margin-top: 50px;">
        <tr>
            <td width="50%">
                <span class="bold">Sold by:</span><br>
                Arctictern Consultancy Services Pvt Ltd<br>
                28th Cross St., Indira Nagar, Adyar<br>
                Chennai, Tamil Nadu 600020<br>
                @if($invoice->office_pan_no)
                    <span class="bold">PAN:</span> {{ strtoupper($invoice->office_pan_no) }}<br>
                @endif
                @if($invoice->office_gst_no)
                    <span class="bold">GST:</span> {{ strtoupper($invoice->office_gst_no) }}<br>
                @endif
            </td>
            <td width="50%">
                <span class="bold">Customer:</span><br>
                {{ strtoupper($invoice->customer_name) }}<br>
                {{ strtoupper($invoice->customer_address) }}<br>
                @if($invoice->customer_gst_no)
                    <span class="bold">GST:</span> {{ strtoupper($invoice->customer_gst_no) }}<br>
                @endif
            </td>
        </tr>
    </table>

    {{-- Tour Details --}}
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Tour Name</th>
                <th>Travel Dates</th>
                <th>No of Pax</th>
                <th>Per Person Cost</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    {{ strtoupper($invoice->destination) }}<br>
                    ({{ $nights }} Nights / {{ $duration }} Days)
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($invoice->travel_from)->format('d-m-Y') }} to 
                    {{ \Carbon\Carbon::parse($invoice->travel_to)->format('d-m-Y') }}
                </td>
                <td>
                    Adults: {{ $invoice->num_adults }}<br>
                    Children: {{ $invoice->num_children }}
                </td>
                <td>
                    Adults: ₹{{ number_format($invoice->adults_cost, 2) }}<br>
                    Child: ₹{{ number_format($invoice->child_cost, 2) }}
                </td>
                <td>
                    ₹{{ number_format($adultsTotalCost + $childTotalCost, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Cost Breakdown --}}
    <div class="section text-right">
        <p><span class="bold">Service Charge:</span> ₹{{ number_format($serviceCost, 2) }}</p>
        <p><span class="bold">GST Rate ({{ $invoice->service_gst }}%):</span> ₹{{ number_format($gstAmount, 2) }}</p>
        <p><span class="bold">Total Amount:</span> ₹{{ number_format($totalAmount, 2) }}</p>
    </div>

    {{-- Signature --}}
    <div class="section text-right">
        <p>For <span class="bold">ARCTICTERN CONSULTANCY SERVICES PVT LTD</span></p>
        <img src="{{ public_path('images/signature.jpg') }}" class="signature-img" width="100" height="50" alt="Signature">
        <p class="bold">Authorized Signatory</p>
    </div>

    {{-- Notes --}}
    @if($invoice->notes)
        <div class="section">
            <p><span class="bold">Notes:</span> {{ strtoupper($invoice->notes) }}</p>
        </div>
    @endif

    {{-- Bank Details --}}
    <div class="section">
        <p><span class="bold">Bank Details:</span></p>
        <p><span class="bold">Bank Name:</span> HDFC &nbsp;&nbsp; <span class="bold">Branch:</span> Adambakkam</p>
        <p><span class="bold">Account:</span> Arctictern Consultancy Services Pvt Ltd</p>
        <p><span class="bold">Account No.:</span> 50200044220791 &nbsp;&nbsp; <span class="bold">IFSC:</span> HDFC0001858</p>
    </div>

</body>
</html>

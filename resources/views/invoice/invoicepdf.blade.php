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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 6px 10px;
            border: 1px solid #000;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: left;
        }

        .no-border td {
            border: none;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .section {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    {{-- Invoice Header --}}
    <div class="text-right">
        <p><strong>Invoice No:</strong> TR2020{{ $invoice->id }}</p>
        <p><strong>Invoice Date:</strong> {{ now()->format('d-m-Y') }}</p>
    </div>

    {{-- Seller & Buyer Info --}}
    <table class="no-border">
        <tr>
            <td width="50%">
                <strong>Sold by:</strong><br>
                Arctictern Consultancy Services Pvt Ltd<br>
                28th Cross St., Indira Nagar, Adyar<br>
                Chennai, Tamil Nadu 600020<br>
                @if($invoice->office_pan_no)
                    <strong>PAN:</strong> {{ strtoupper($invoice->office_pan_no) }}<br>
                @endif
                @if($invoice->office_gst_no)
                    <strong>GST:</strong> {{ strtoupper($invoice->office_gst_no) }}<br>
                @endif
            </td>
            <td width="50%">
                <strong>Customer:</strong><br>
                {{ strtoupper($invoice->customer_name) }}<br>
                {{ strtoupper($invoice->customer_address) }}<br>
                @if($invoice->customer_gst_no)
                    <strong>GST:</strong> {{ strtoupper($invoice->customer_gst_no) }}<br>
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
                <td>{{ strtoupper($invoice->destination) }}<br>({{ $nights }} Nights / {{ $duration }} Days)</td>
                <td>{{ \Carbon\Carbon::parse($invoice->travel_from)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($invoice->travel_to)->format('d-m-Y') }}</td>
                <td>Adults: {{ $invoice->num_adults }}<br>Children: {{ $invoice->num_children }}</td>
                <td>Adults: ₹{{ number_format($invoice->adults_cost, 2) }}<br>Child: ₹{{ number_format($invoice->child_cost, 2) }}</td>
                <td>₹{{ number_format($adultsTotalCost + $childTotalCost, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Cost Breakdown --}}
    <div class="text-right section">
        <p><strong>Service Charge:</strong> ₹{{ number_format($serviceCost, 2) }}</p>
        <p><strong>GST Rate ({{ $invoice->service_gst }}%):</strong> ₹{{ number_format($gstAmount, 2) }}</p>
        <p><strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 2) }}</p>
    </div>

    {{-- Signature --}}
    <div class="text-right section">
        <p>For <strong>ARCTICTERN CONSULTANCY SERVICES PVT LTD</strong></p>
        <img src="{{ public_path('images/signature.jpg') }}" width="100" height="50" alt="Signature">
        <p><strong>Authorized Signatory</strong></p>
    </div>

    {{-- Notes --}}
    @if($invoice->notes)
        <div class="section">
            <strong>Notes:</strong> {{ strtoupper($invoice->notes) }}
        </div>
    @endif

    {{-- Bank Details --}}
    <div class="section">
        <p><strong>Bank Details:</strong></p>
        <p><strong>Bank Name:</strong> HDFC &nbsp; <strong>Branch:</strong> Adambakkam</p>
        <p><strong>Account:</strong> Arctictern Consultancy Services Pvt Ltd</p>
        <p><strong>Account No.:</strong> 50200044220791 &nbsp; <strong>IFSC:</strong> HDFC0001858</p>
    </div>

</body>
</html>

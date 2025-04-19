<!DOCTYPE html>
<html>
<head>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>

    <div style='text-align: right; margin-top: 6px;'>
        <p><strong>Invoice No:</strong> TR2020{{ $invoice->id }}</p>
        <p><strong>Invoice Date:</strong> {{ now()->format('d-m-Y') }}</p>
    </div>

    <table>
        <tr>
            <td width="50%">
                <strong>Sold by:</strong><br>
                Arctictern Consultancy Services Pvt Ltd<br>
                28th Cross St., Indira Nagar, Adyar<br>
                Chennai, Tamil Nadu 600020<br>
                @if($invoice->office_pan_no)<strong>PAN:</strong> {{ strtoupper($invoice->office_pan_no) }}<br>@endif
                @if($invoice->office_gst_no)<strong>GST:</strong> {{ strtoupper($invoice->office_gst_no) }}<br>@endif
            </td>
            <td width="50%">
                <strong>Customer:</strong><br>
                {{ strtoupper($invoice->customer_name) }}<br>
                {{ strtoupper($invoice->customer_address) }}<br>
                @if($invoice->customer_gst_no)<strong>GST:</strong> {{ strtoupper($invoice->customer_gst_no) }}<br>@endif
            </td>
        </tr>
    </table>

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
        <tr>
            <td>1</td>
            <td>{{ strtoupper($invoice->destination) }} <br>({{ $nights }} Nights / {{ $duration }} Days)</td>
            <td>{{ \Carbon\Carbon::parse($invoice->travel_from)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($invoice->travel_to)->format('d-m-Y') }}</td>
            <td>Adults: {{ $invoice->num_adults }} / Child: {{ $invoice->num_children }}</td>
            <td>Adults: ₹{{ number_format($invoice->adults_cost, 2) }}<br>Child: ₹{{ number_format($invoice->child_cost, 2) }}</td>
            <td>₹{{ number_format($adultsTotalCost + $childTotalCost, 2) }}</td>
        </tr>
    </table>

    <div style="text-align: right; margin-top: 20px;">
        <p><strong>Service Charge:</strong> ₹{{ number_format($serviceCost, 2) }}</p>
        <p><strong>GST RATE ({{ $invoice->service_gst }}%):</strong> ₹{{ number_format($gstAmount, 2) }}</p>
        <p><strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 2) }}</p>
    </div>

    <div style="text-align: right; margin-top: 20px;">
        <p>For ARCTICTERN CONSULTANCY SERVICES PVT LTD</p>
        <img src="{{ public_path('images/signature.jpg') }}" width="100" height="50" style="margin-top: 6px;">
        <p>Authorized Signatory</p>
    </div>

    @if($invoice->notes)
        <div><strong>Notes:</strong> {{ strtoupper($invoice->notes) }}</div>
    @endif

    <div style="margin-top: 20px;">
        <p><strong>Bank Details:</strong></p>
        <p><strong>Bank Name:</strong> HDFC <strong>Branch:</strong> Adambakkam</p>
        <p><strong>Account:</strong> Arctictern Consultancy Services Pvt Ltd</p>
        <p><strong>Account No.:</strong> 50200044220791 <strong>IFSC:</strong> HDFC0001858</p>
    </div>

</body>
</html>

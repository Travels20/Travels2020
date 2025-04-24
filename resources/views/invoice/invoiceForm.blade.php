@extends('layouts.itineraryapp')

<div class="container mt-4">
        <div class="card w-100 w-md-75 mx-auto">
            <div class="card-body">
                <h5 class="card-title text-center">Customer Details</h5>
                <!-- <form id="invoiceForm" > -->
                <form id="invoiceForm" method="POST" action="{{ route('invoices.store') }}">
                @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Customer Name:</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_address" class="form-label">Address:</label>
                            <input type="text" id="customer_address" name="customer_address" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gst_no" class="form-label">Customer GST Number:</label>
                            <input type="text" id="gst_no" name="gst_no" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="travelFrom" class="form-label">Traveling Dates</label>
                            <div class="input-group">
                                <span class="input-group-text">Departure</span>
                                <input type="date" class="form-control" id="travelFrom" name="travelFrom" required>
                                <span class="input-group-text">Return</span>
                                <input type="date" class="form-control" id="travelTo" name="travelTo" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tourName" class="form-label">Tour Name / Destination</label>
                            <select class="form-select" id="selectdestination" name="destination" onchange="toggleDestination(this)">
                                <option selected disabled>Select Destination</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Dubai">Dubai</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Bali">Bali</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Others">Others</option>
                            </select>
                            <input type="text" class="form-control mt-2 d-none" id="destinationOthers" name="destinationOthers" placeholder="Enter Destination Name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Passengers</label>
                            <div class="input-group">
                                <label class="input-group-text" for="numAdults">Adults</label>
                                <input type="number" class="form-control" id="numAdults" name="numAdults" value="1" min="1" max="100" required>
                                <label class="input-group-text" for="numChildren">Children</label>
                                <input type="number" class="form-control" id="numChildren" name="numChildren" value="0" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="adults_cost" class="form-label">Adults Cost:</label>
                            <input type="number" id="adults_cost" name="adults_cost" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="child_cost" class="form-label">Child Cost:</label>
                            <input type="number" id="child_cost" name="child_cost" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="service_cost" class="form-label">Service Cost</label>
                            <input type="number" id="service_cost" name="service_cost" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="service_gst" class="form-label">Service GST %</label>
                            <input type="number" id="service_gst" name="service_gst" class="form-control" required>
                        </div>
                        <!-- <div class="col-md-6 mb-3">
                            <label for="invoicenotes" class="form-label">Important Notes:</label>
                            <textarea id="invoicenotes" name="invoicenotes" class="form-control"></textarea> 
                         </div> -->
                        <div class="col-md-6 mb-3">
                            <label for="office_gst_no" class="form-label">Office GST Number:</label>
                            <input type="text" id="office_gst_no" name="office_gst_no" class="form-control" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="office_pan_no" class="form-label">Office PAN Number:</label>
                            <input type="text" id="office_pan_no" name="office_pan_no" class="form-control" >
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/adminItinerary" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary" id="InvoiceBtn">Generate Invoice</button>

                        
                    </div>
                </form>
            </div>
        </div>
 </div>
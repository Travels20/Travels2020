@extends('layouts.itineraryapp')

<div class="container mt-4">
    <div class="card w-100 w-md-75 mx-auto">
        <div class="card-body">
            <h5 class="card-title text-center">Tour Booking Form</h5>
            <form action="{{ route('itinerary.store') }}" method="POST" enctype="multipart/form-data">
            @csrf 
                <div class="row">
                  
                    <!-- Trip ID (display-only) -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="tripIdDisplay" class="form-label">Trip ID</label>
                        <input type="text" class="form-control" id="tripIdDisplay" value="{{ $generatedTripId }}" disabled>
                    </div>

                    <!-- Hidden input to submit -->
                    <input type="hidden" name="tripId" id="tripId" value="{{ $generatedTripId }}">

                                    

                    <!-- User Name -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="userName" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter User Name">
                    </div>

                    <!-- Tour Name / Place -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="tourName" class="form-label">Tour Name / Place</label>
                        <input type="text" class="form-control" id="tourName" name="tourName" placeholder="Enter Tour Name or Place">
                    </div>

                    <!-- Map Image -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Map Image</label>
                        <input type="file" class="form-control" id="timages" name="timages" accept="image/*">
                        <div id="imagehide">
                            <img id="tourImagePreview" src="#" alt="Tour Image" style="width: 80px; height: 80px;">
                        </div>
                    </div>

                    <!-- Check-in Date -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="checkIn" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="checkIn" name="checkIn">
                    </div>

                    <!-- Check-out Date -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="checkOut" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="checkOut" name="checkOut">
                    </div>

                    <!-- Number of Passengers -->
                    <div class="col-12 mt-3">
                        <h5>Number of Passengers</h5>
                    </div>

                    <!-- Adults -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Adults</label>
                        <input type="number" class="form-control" id="numAdults" name="numAdults" value="1" min="1">
                    </div>

                    <!-- Children -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Children</label>
                        <input type="number" class="form-control" id="numChildren" name="numChildren" value="0" min="0">
                    </div>

                    <!-- Landpackage Cost -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="cost" class="form-label">Landpackage Cost</label>
                        <textarea class="form-control" id="cost" name="cost" style="height: 100px"></textarea>
                    </div>

                    <!-- Inclusion -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="inclusion" class="form-label">Inclusion</label>
                        <textarea class="form-control" id="inclusion" name="inclusion" style="height: 100px"></textarea>
                    </div>

                    <!-- Exclusion -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="exclusion" class="form-label">Exclusion</label>
                        <textarea class="form-control" id="exclusion" name="exclusion" style="height: 100px"></textarea>
                    </div>

                    <!-- Notes -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="notes" class="form-label">Important Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" style="height: 100px"></textarea>
                    </div>

                    <!-- Hotel -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="hotel" class="form-label">Hotel</label>
                        <textarea class="form-control" id="hotel" name="hotel" style="height: 100px"></textarea>
                    </div>

                    <!-- Flight -->
                    <div class="col-12 col-md-6 mb-3">
                        <label for="flight" class="form-label">Flight</label>
                        <textarea class="form-control" id="flight" name="flight" style="height: 100px"></textarea>
                    </div>

                    <!-- Flight Image -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Flight Image (Max: 300px * 200px)</label>
                        <input type="file" class="form-control" id="flightimages" name="flightimages" accept="image/*">
                        <div id="imagehideflight">
                            <img id="tourImagePreviewflight" src="#" style="width: 80px; height: 80px">
                        </div>
                    </div>

                    
                </div>
                <hr>
                <h5 class="text-center">Officer Details</h5>
                <div class="row">
                    <!-- Officer Name -->
                    <div class="col-12 col-md-6 mb-3">
                            <label for="OfficerName" class="form-label">Officer Name</label>
                            <input type="text" class="form-control" id="officerName" name="officerName" placeholder="Enter Officer Name">
                        </div>

                    <!-- Officer Image -->
                    <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Officer Image (Max: 100px * 100px)</label>
                            <input type="file" class="form-control image-upload" id="officerimage" name="officerimage" accept="image/*">
                            <!-- <img src="" class="img-preview mt-2" id="tourImagePreviewofficer" style="width: 200px; height: 200px"> -->
                    </div>
                        
                </div>        

                <hr>
                <h5 class="text-center">Vacation Summary</h5>

                <div id="daysContainer">
                   
                </div>

                <div class="mt-3">
                    <label>Select PDF Type:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pdfType" id="singlePdf" value="Single Pdf" checked>
                        <label class="form-check-label" for="singlePdf">Single PDF</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pdfType" id="multiplePdf" value="Multiple Pdf">
                        <label class="form-check-label" for="multiplePdf">Multiple PDF</label>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <div class="d-inline-block">
                        <a href="/adminItinerary" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="d-inline-block">
                        <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                    </div>
                    <div class="d-inline-block">
                        <button type="submit" class="btn btn-primary" id="updateButton">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

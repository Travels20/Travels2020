<!DOCTYPE html>
<html>
<head>
    <title> Itinerary</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 5 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="container mt-4">
    <div class="card w-100 w-md-75 mx-auto">
        <div class="card-body">
            <h5 class="card-title text-center">Tour Booking Form</h5>
            <form method="POST" action="{{ route('itinerary.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <input type="hidden" class="form-control" id="id" name="id">

                    <div class="col-12 col-md-6 mb-3">
                        <label for="tripId" class="form-label">Trip ID</label>
                        <input type="text" class="form-control" id="tripId" name="tripId" placeholder="Enter Trip ID">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="userName" class="form-label">UserName</label>
                        <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter User Name">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="tourName" class="form-label">Tour Name / Place</label>
                        <input type="text" class="form-control" id="tourName" name="tourName" placeholder="Enter Tour Name or Place">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Map Image</label>
                        <input type="file" class="form-control" id="timages" name="timages" accept="image/*">
                        <div id="imagehide">
                            <img id="tourImagePreview" src="#" alt="Tour Image" style="width: 80px; height: 80px;">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="checkIn" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="checkIn" name="checkIn">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="checkOut" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="checkOut" name="checkOut">
                    </div>

                    <div class="col-12 mt-3">
                        <h5>Number of Passengers</h5>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Adults</label>
                        <input type="number" class="form-control" id="numAdults" name="numAdults" value="1" min="1">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Children</label>
                        <input type="number" class="form-control" id="numChildren" name="numChildren" value="0" min="0">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="cost" class="form-label">Landpackage Cost</label>
                        <textarea class="form-control" id="cost" name="cost" style="height: 100px"></textarea>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="inclusion" class="form-label">Inclusion</label>
                        <textarea class="form-control" id="inclusion" name="inclusion" style="height: 100px"></textarea>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="exclusion" class="form-label">Exclusion</label>
                        <textarea class="form-control" id="exclusion" name="exclusion" style="height: 100px"></textarea>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="notes" class="form-label">Important Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" style="height: 100px"></textarea>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="hotel" class="form-label">Hotel</label>
                        <textarea class="form-control" id="hotel" name="hotel" style="height: 100px"></textarea>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="flight" class="form-label">Flight</label>
                        <textarea class="form-control" id="flight" name="flight" style="height: 100px"></textarea>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Flight Image (Max: 300px * 200px)</label>
                        <input type="file" class="form-control" id="flightimages" name="flightimages" accept="image/*">
                        <div id="imagehideflight">
                            <img id="tourImagePreviewflight" src="#" style="width: 80px; height: 80px">
                        </div>
                    </div>
                </div>

                <hr>
                <h5 class="text-center">Vacation Summary</h5>

                <div id="daysContainer">
                    <div class="day-form border rounded p-3 mb-3" id="day1">
                        <h6 class="mb-3">Day 1</h6>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <input type="date" class="form-control" name="date[]">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">Day 1 Image</label>
                                <input type="file" class="form-control" name="images[]" accept="image/*">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <textarea class="form-control" name="itinerary[]" placeholder="Enter itinerary details" style="height: 100px" required></textarea>
                            </div>
                        </div>
                    </div>
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
                    <a href="/" class="btn btn-secondary">Back</a>
                    <button id="submitButton" type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- <div class="container">
        <h2>Create a Travel Itinerary</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('itinerary.store') }}">
            @csrf
            <label>Title:</label><br>
            <input type="text" name="title" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" required></textarea><br><br>

            <label>Travel Date:</label><br>
            <input type="date" name="travel_date" required><br><br>

            <button type="submit">Save</button>
        </form>
    </div> -->

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
   

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>

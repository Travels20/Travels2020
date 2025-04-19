<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Travels2020 Customer Details</title>
    <link rel="shortcut icon" href="../images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <!-- Tour Booking details -->
        <div class="card w-100 w-md-75 mx-auto">
            <div class="card-body">
                <h5 class="card-title text-center">Customer Details</h5>
                <form method="POST" action="{{ route('customers.store') }}" id="customerForm" enctype="multipart/form-data">
                     @csrf
                    <div class="row">

                     <!-- Travel Dates -->
                     <div class="col-md-6 mb-3">
                            <label for="travelFrom" class="form-label">Traveling Dates</label>
                            <div class="input-group">
                                <span class="input-group-text">Departure</span>
                                <input type="date" class="form-control" id="travelFrom" name="travelFrom" required>
                                <span class="input-group-text">Return</span>
                                <input type="date" class="form-control" id="travelTo" name="travelTo" required>
                            </div>
                            <!-- <small class="text-muted">* Select the start and end dates of your trip.</small> -->
                        </div>

                     <!-- Destination Selection -->
                        <div class="col-md-6 mb-3">
                            <label for="tourName" class="form-label">Tour Name / Destination</label>
                            
                            <select class="form-select" id="destinationSelect" name="destination" onchange="toggleDestinationInput()">
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

                            <!-- Hidden Input Field (Initially Hidden) -->
                            <input type="text" class="form-control mt-2 d-none" id="destinationinput" name="destination2" placeholder="Enter Destination Name">
                        </div>

                         <div class="col-md-6 mb-3">
                            <label for="relationship" class="form-label">Travelling Type</label>
                            
                            <select class="form-select" id="relationshipSelect" name="relationship">
                                <option selected disabled>Select Person</option>
                                <option value="Family">Family</option>
                                <option value="Couples">Couples</option>
                                <option value="Friends">Friends</option>
                                <option value="Solo">Solo</option>
                            </select>
                         </div>
                         <!-- Number of Passengers -->
                         <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Passengers</label>
                            <div class="input-group">
                                <label class="input-group-text" for="numAdults">Adults</label>
                                <input type="number" class="form-control" id="numAdults" name="numAdults" value="1" 
                                    min="1" max="100" required>
                                <label class="input-group-text" for="numChildren">Children</label>
                                <input type="number" class="form-control" id="numChildren" name="numChildren" value="0" 
                                    min="0" max="100">
                            </div>
                            <!-- <small class="text-muted">* Enter the number of adults and children.</small> -->
                         </div>
                    </div>
                </form>
                <div class="accordion" id="passengerAccordion"></div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="text-center mt-3">
            <a href="/adminItinerary" class="btn btn-secondary">Back</a>
            <button type="button" id="reviewButton" class="btn btn-success">Review</button>
              <button type="button" id="updateButton" class="btn btn-primary">Update</button>
        </div>

    </div>

     <!-- Review Modal -->
     <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review Your Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="reviewDetails"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
                <button type="button" id="confirmSubmit" class="btn btn-success">Confirm & Submit</button>
            </div>
        </div>
    </div>
</div>

    <script>
    function toggleDestinationInput() {
        let destinationSelect = document.getElementById("destinationSelect");
        let tourNameInput = document.getElementById("destinationinput");

        if (destinationSelect.value === "Others") {
            tourNameInput.classList.remove("d-none"); // Show input field
        } else {
            tourNameInput.classList.add("d-none"); // Hide input field
            tourNameInput.value = ""; // Clear input field
        }
    }


</script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="js/customer.js"></script> -->
    <script src="{{ asset('js/customer.js') }}"></script>
</body>

</html>

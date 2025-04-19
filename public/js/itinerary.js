document.addEventListener("DOMContentLoaded", function () {
    // Event listener for check-in and check-out date changes
    const checkInInput = document.getElementById("checkIn");
    const checkOutInput = document.getElementById("checkOut");

    // Function to calculate days between check-in and check-out
    function calculateDays() {
        let checkIn = checkInInput.value;
        let checkOut = checkOutInput.value;
        let daysContainer = document.getElementById("daysContainer");

        daysContainer.innerHTML = ""; // Clear the existing day forms

        if (!checkIn || !checkOut) return;

        let startDate = new Date(checkIn);
        let endDate = new Date(checkOut);

        if (startDate >= endDate) {
            alert("Check-out date must be after check-in date!");
            return;
        }

        let $daysbetween = [];

        while (startDate <= endDate) {
            let formattedDate = new Date(startDate).toISOString().split("T")[0]; // Format YYYY-MM-DD
            $daysbetween.push(formattedDate);
            startDate.setDate(startDate.getDate() + 1);
        }

        // Generate and add day forms
        $daysbetween.forEach((date, index) => {
            let dayCount = index + 1;
            let newDay = document.createElement("div");
            newDay.classList.add("day-form", "border", "rounded", "p-3", "mb-3");
            newDay.id = `day${dayCount}`;
            newDay.innerHTML = `
                <h6 class="mb-3">Day ${dayCount}</h6>
                <div class="row">
                    <!-- Stay -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay" required>
                            <label>Stay</label>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="date[]" value="${date}" required>
                            <label>Date</label>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Day ${dayCount} Image (Max: 300px width, 200px height)</label>
                        <input type="file" class="form-control image-upload" name="images[]" accept="image/*" required>
                    </div>

                    <!-- Itinerary Content -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control itinerary-editor" name="itinerary[]" placeholder="Enter itinerary details" style="height: 100px" required></textarea>
                        </div>
                    </div>
                </div>
            `;

            daysContainer.appendChild(newDay);

            // Initialize CKEditor for each dynamically created textarea
            ClassicEditor.create(newDay.querySelector('.itinerary-editor'))
                .catch(error => {
                    console.error("Error initializing CKEditor:", error);
                });
        });
    }

    // Initialize the day calculation on page load if dates are already filled
    if (checkInInput.value && checkOutInput.value) {
        calculateDays();
    }

    checkInInput.addEventListener("change", calculateDays);
    checkOutInput.addEventListener("change", calculateDays);

    // Form submission logic
    const submitButton = document.getElementById("submitButton");

    submitButton.addEventListener("click", function () {
        handleSubmit(submitButton);
    });

    function handleSubmit(button) {
        button.innerHTML = `
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;

        const formData = new FormData();
        const errorMessages = [];

        const dayForms = document.querySelectorAll("#daysContainer .day-form");
        const pdfType = document.querySelector("input[name='pdfType']:checked").value;

        // Collect vacation_summary (daily itinerary) data
        dayForms.forEach((day, i) => {
            const stay = day.querySelector("input[name='stay[]']").value.trim();
            const date = day.querySelector("input[name='date[]']").value.trim();
            const itinerary = day.querySelector("textarea[name='itinerary[]']").value.trim();
            const vsImage = day.querySelector("input[name='images[]']").files[0];

            formData.append(`stay[]`, stay);
            formData.append(`date[]`, date);
            formData.append(`itinerary[]`, itinerary);
            if (vsImage) {
                formData.append(`images[]`, vsImage);
            }
        });

        // Basic trip fields
        const fields = ["userName", "tourName", "checkIn", "checkOut", "numAdults", "numChildren"];
        for (let field of fields) {
            const input = document.getElementById(field);
            if (!input || !input.value.trim()) {
                errorMessages.push(`Please fill in the ${field}.`);
                break;
            }
            formData.append(field, input.value.trim());
        }

        const tripIdInput = document.getElementById("tripId"); // hidden input
        if (tripIdInput && tripIdInput.value.trim()) {
            formData.append("tripId", tripIdInput.value.trim());
        }
        

        // Upload tour/flight images
        const tourImage = document.getElementById("timages").files[0];
        const flightImage = document.getElementById("flightimages").files[0];
        const officerImage = document.getElementById("officerimage").files[0];
        if (tourImage) formData.append("timages", tourImage);
        if (flightImage) formData.append("flightimages", flightImage);
        if (officerImage) formData.append("officerimage", officerImage);

        // Editor data
        formData.append("inclusion", inclusionvalue.getData());
        formData.append("exclusion", exclusionvalue.getData());
        formData.append("notes", notevalue.getData());
        formData.append("cost", costvalue.getData());
        formData.append("hotel", hotelvalue.getData());
        formData.append("flight", flightvalue.getData());

        if (errorMessages.length) {
            alert(errorMessages.join("\n"));
            button.innerHTML = "Save";
            return;
        }

        // Submit form data using FormData (multipart form submission)
        fetch("/itinerary/store", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData  // Send the FormData directly, no JSON
        })
        .then(res => res.json())
        .then(data => {
            console.log('Server response:', data);
            if (data && data.id) {
                const tourId = data.id;
                const redirectUrl = pdfType === 'Single Pdf'
                    ? `/singlepdf-itinerary/${tourId}`
                    : `/generatepdf.php?id=${tourId}`;

                window.location.href = redirectUrl;
            } else {
                alert("Error processing request.");
                button.innerHTML = "Save";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while submitting.");
            button.innerHTML = "Save";
        });
    }

    // Initialize CKEditor for other fields dynamically
    var hotelvalue, flightvalue, inclusionvalue, exclusionvalue, notevalue, costvalue;

    function initializeEditor(selector, callback) {
        const element = document.querySelector(selector);
        if (element) {
            ClassicEditor.create(element)
                .then(editor => {
                    callback(editor);
                    editor.model.document.on('change:data', () => {
                        console.log(`${selector} content changed:`, editor.getData());
                    });
                })
                .catch(error => console.error(`Error initializing ${selector}:`, error));
        } else {
            console.error(`Element ${selector} not found in the DOM.`);
        }
    }

    // Initialize each CKEditor
    initializeEditor('#hotel', editor => hotelvalue = editor);
    initializeEditor('#flight', editor => flightvalue = editor);
    initializeEditor('#inclusion', editor => inclusionvalue = editor);
    initializeEditor('#exclusion', editor => exclusionvalue = editor);
    initializeEditor('#notes', editor => notevalue = editor);
    initializeEditor('#cost', editor => costvalue = editor);

    // Helper function to adjust adult/children count
    function adjustCount(selector, delta, min) {
        const input = document.querySelector(selector);
        let value = parseInt(input.value) || 0;
        input.value = Math.max(min, value + delta);
    }

    // Edit Mode Handling (Show or Hide elements)
    const myParam = new URLSearchParams(window.location.search).get('id');
    if (myParam) {
        if (typeof editTour === 'function') {
            editTour(myParam);
        }
        document.getElementById('submitButton').style.display = 'none';
        document.getElementById('imagehide').style.display = 'block';
        document.getElementById('imagehideflight').style.display = 'block';
    } else {
        document.getElementById('submitButton').style.display = 'block';
        const updateButton = document.getElementById('updateButton');
        if (updateButton) updateButton.style.display = 'none';
        document.getElementById('imagehide').style.display = 'none';
        document.getElementById('imagehideflight').style.display = 'none';
    }
});

// Add Day Function - Corrected to use ClassicEditor instead of CKEditor
function addDay(stay, date, image, itinerary, dayCount) {
    let newDay = document.createElement("div");
    newDay.classList.add("day-container", "update-day-form");

    let uniqueId = `itinerary-${dayCount}`; // Generate a unique ID for each itinerary field

    newDay.innerHTML = `
        <h6 class="mb-3">Day ${dayCount}</h6>
        <div class="row">
            <!-- Stay -->
            <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay" value="${stay}" required>
                    <label>Stay</label>
                </div>
            </div>

            <!-- Date -->
            <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                    <input type="date" class="form-control" name="date[]" value="${date}" required>
                    <label>Date</label>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Day ${dayCount} Image (Max: 300px width, 200px height)</label>
                <input type="file" class="form-control image-upload" name="images[]" accept="image/*" required>
                <img src="${image}" class="img-preview mt-2" style="width: 80px; height: 80px">
            </div>

            <!-- Itinerary Content -->
            <div class="col-12 col-md-6 mb-3">
                <div class="form-floating">
                    <textarea id="${uniqueId}" class="form-control itinerary-editor" name="itinerary[]" placeholder="Enter itinerary details" style="height: 100px" required>${itinerary}</textarea>
                </div>
            </div>
        </div>
    `;

    document.getElementById("daysContainer").appendChild(newDay);

    // Initialize CKEditor (using ClassicEditor)
    ClassicEditor.create(newDay.querySelector(`#${uniqueId}`))
        .catch(error => console.error("Error initializing CKEditor:", error));
}

function toggleDestination(selectElement) {
    let destinationInput = document.getElementById("destinationOthers");
    if (selectElement.value === "Others") {
        destinationInput.classList.remove("d-none");
        destinationInput.required = true;
    } else {
        destinationInput.classList.add("d-none");
        destinationInput.required = false;
        destinationInput.value = "";
    }
}

$(document).ready(function () {
    $('#InvoiceBtn').click(function () {
        let isValid = true;
        
        // Validate required fields
        $('#invoiceForm [required]').each(function () {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            alert('Please fill all required fields.');
            return;
        }

        // Serialize form data
        let formData = $('#invoiceForm').serialize();

        $.ajax({
            url: "{{ route('invoices.store') }}",
            method: "POST",
            data: formData,
            success: function (response) {
                if (response.invoice_id) {
                    alert('Invoice saved!');
                    // Trigger PDF generation and download
                    window.location.href = "/invoicepdf/" + response.invoice_id;  // Redirect to the PDF download route
                } else {
                    alert('Unexpected error.');
                }
            },
            error: function (xhr) {
                alert('Error: could not save');
            }
        });
    });
});

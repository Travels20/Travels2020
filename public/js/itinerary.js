document.addEventListener("DOMContentLoaded", function () {
    // Event listener for check-in and check-out date changes
    const checkInInput = document.getElementById("checkIn");
    const checkOutInput = document.getElementById("checkOut");
    const editors = [];

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
                            <input type="text" class="form-control" name="stay[]" placeholder="Enter Stay">
                            <label>Stay</label>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="date[]" value="${date}">
                            <label>Date</label>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Day ${dayCount} Image (Max: 300px width, 200px height)</label>
                        <input type="file" class="form-control image-upload" name="images[]" accept="image/*">
                    </div>

                    <!-- Itinerary Content -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control itinerary-editor" name="itinerary-content[]" placeholder="Enter itinerary details" style="height: 100px"></textarea>
                        </div>
                    </div>
                </div>
            `;

            daysContainer.appendChild(newDay);

            // Initialize CKEditor for the newly added textarea
            ClassicEditor.create(newDay.querySelector('.itinerary-editor'))
                .then(editor => {
                    editors[index] = editor;
                })
                .catch(error => {
                    console.error("CKEditor Init Error:", error);
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

    submitButton.addEventListener("click", function (e) {
        e.preventDefault();
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
        const pdfType = document.querySelector("input[name='pdfType']:checked")?.value || "Single Pdf";



        // Collect vacation_summary
        dayForms.forEach((day, i) => {

            const stay = day.querySelector("input[name='stay[]']").value.trim();
            const date = day.querySelector("input[name='date[]']").value.trim();
            // const itinerary = day.querySelector("textarea[name='itinerary_content[]']").value.trim();
            const itinerary = editors[i]?.getData().trim() || '';
            const vsImage = day.querySelector("input[name='images[]']").files[0];

            formData.append("stay[]", stay);
            formData.append("date[]", date);
            formData.append("itinerary_content[]", itinerary);
            if (vsImage) {
                formData.append("images[]", vsImage);
            }
        });

        // Required fields
        const fields = ["userName", "tourName", "checkIn", "checkOut", "numAdults", "officerName"];
        for (let field of fields) {
            const input = document.getElementById(field);
            if (!input || !input.value.trim()) {
                errorMessages.push(`Please fill in the ${field}.`);
            } else {
                formData.append(field, input.value.trim());
            }
        }

        // Optional fields
        const optionalFields = ["numChildren"];
        optionalFields.forEach(field => {
            const input = document.getElementById(field);
            if (input && input.value.trim()) {
                formData.append(field, input.value.trim());
            }
        });

        const tripIdInput = document.getElementById("tripId");
        if (tripIdInput && tripIdInput.value.trim()) {
            formData.append("tripId", tripIdInput.value.trim());
        }

        // Uploads
        const tourImage = document.getElementById("timages").files[0] || null;
        const flightImage = document.getElementById("flightimage").files[0] || null;
        const officerImage = document.getElementById("officerimage").files[0] || null;
    
        if (tourImage) formData.append("tourImage", tourImage);
        if (flightImage) formData.append("flightimage", flightImage);
        if (officerImage) formData.append("officerimage", officerImage);

        // CKEditor fields
        formData.append("inclusion", inclusionvalue.getData());
        formData.append("exclusion", exclusionvalue.getData());
        formData.append("notes", notevalue.getData());
        formData.append("cost", costvalue.getData());
        formData.append("hotel", hotelvalue.getData());
        formData.append("flight", flightvalue.getData());

        if (errorMessages.length > 0) {
            alert(errorMessages.join("\n"));
            button.innerHTML = "Save";
            return;
        }

        fetch("/itinerary/store", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log("Server response:", data);
        
            if (data && data.id) {
                const tourId = data.id;
                const redirectUrl = pdfType === "Single Pdf"
                    ? `/singlepdf-itinerary/${tourId}`
                    : `/generatepdf.php?id=${tourId}`;
        
                window.location.href = redirectUrl;
            } else {
                alert("Error processing request.");
            }
        
            button.innerHTML = "Save";
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
                        // console.log(`${selector} content changed:`, editor.getData());
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
});

$(document).ready(function () {

    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.get('id');
    if (myParam) {
        editTour(myParam);
        $('#submitButton').hide();
        $('#imagehide').show();
        $('#imagehideflight').show();
        $('#imagehideofficer').show();

    } else {
        $('#submitButton').show();
        $('#updateButton').hide();
        $('#imagehide').hide();
        $('#imagehideflight').hide();
        $('#imagehideofficer').hide();
    }
    let $daysbetween = [];

    document.getElementById("checkIn").addEventListener("change", calculateDays);
    document.getElementById("checkOut").addEventListener("change", calculateDays);


});

function editTour(tripId) {
        // Show the form
        document.getElementById("tourForm");

    fetch(`/itineraryform/${tripId}`)
       .then(response => response.json())
        .then(data => {
            if (data.success) {
                const trip = data.trip;

                let daysContainer = document.getElementById("daysContainer");

                // Clear previous day forms
                daysContainer.innerHTML = "";

                // Populate form fields
                document.getElementById("id").value = trip.id; // Assuming trip_id is disabled
                document.getElementById("tripId").value = trip.trip_id; // Assuming trip_id is disabled
                document.getElementById("userName").value = trip.username;
                document.getElementById("tourName").value = trip.tour_name;
                document.getElementById("checkIn").value = trip.check_in;
                document.getElementById("checkOut").value = trip.check_out;
                document.getElementById("numAdults").value = trip.adults;
                document.getElementById("numChildren").value = trip.children;
                inclusionvalue.setData(trip.inclusion);
                exclusionvalue.setData(trip.exclusion);
                notevalue.setData(trip.notes);
                costvalue.setData(trip.cost);
                hotelvalue.setData(trip.hotel);
                flightvalue.setData(trip.flight);
                // Show image preview if available
                if (trip.tour_image) {
                    document.getElementById("tourImagePreview").src = trip.tour_image;
                    document.getElementById("tourImagePreview");
                }
                if (trip.tour_image) {
                    document.getElementById("tourImagePreviewflight").src = trip.ftimage;
                    document.getElementById("tourImagePreviewflight");
                }

                if (data && data.vacation_summary && data.vacation_summary.length > 0) {
                    var vsummary = data.vacation_summary;

                    for (let i = 0; i < vsummary.length; i++) {
                        addDay(vsummary[i].stay, vsummary[i].date, vsummary[i].image, vsummary[i].itinerary_content, i + 1);
                    }
                }
            } else {
                alert("Error fetching trip details: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error fetching trip details:", error);
        });
}


// function editTour(tripId) {
//      // Show the form
//      document.getElementById("tourForm");

//     fetch(`/itineraryform/${tripId}`)
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error(`HTTP error! status: ${response.status}`);
//             }
//             return response.json();
//         })
//         .then(data => {
//             if (data.success) {
//                 const trip = data.trip;
//                 // const vacationSummary = data.vacation_summary;
//                 // const daysContainer = document.getElementById("daysContainer");
//                 let daysContainer = document.getElementById("daysContainer");

//                 // Clear previous content
//                 daysContainer.innerHTML = "";
//                 window.location.href = `/itinerary/itineraryform`;

//                 // Populate general tour details
//                 document.getElementById("id").value = trip.id;
//                 document.getElementById("tripId").value = trip.trip_id;
//                 document.getElementById("userName").value = trip.username;
//                 document.getElementById("tourName").value = trip.tour_name;
//                 document.getElementById("checkIn").value = trip.check_in;
//                 document.getElementById("checkOut").value = trip.check_out;
//                 document.getElementById("numAdults").value = trip.adults;
//                 document.getElementById("numChildren").value = trip.children;

//                 // Set CKEditor contents
//                 inclusionvalue.setData(trip.inclusion || '');
//                 exclusionvalue.setData(trip.exclusion || '');
//                 notevalue.setData(trip.notes || '');
//                 costvalue.setData(trip.cost || '');
//                 hotelvalue.setData(trip.hotel || '');
//                 flightvalue.setData(trip.flight || '');

//                 if (trip.map_image) {
//                     document.getElementById("tourImagePreview").src = trip.map_image;
//                     document.getElementById("tourImagePreview");
//                 }
//                 if (trip.flightimage) {
//                     document.getElementById("tourImagePreviewflight").src = trip.flightimage;
//                     document.getElementById("tourImagePreviewflight");
//                 }
//                 if (trip.officerimage) {
//                     document.getElementById("tourImagePreviewofficer").src = trip.officerimage;
//                     document.getElementById("tourImagePreviewofficer");
//                 }

//                 if (data && data.vacation_summary && data.vacation_summary.length > 0) {
//                     var vsummary = data.vacation_summary;

//                     for (let i = 0; i < vsummary.length; i++) {
//                         addDay(vsummary[i].stay, vsummary[i].date, vsummary[i].image, vsummary[i].itinerary_content, i + 1);
//                     }
//                 }
//             } else {
//                 alert("Error fetching trip data: " + (data.message || "Unknown error."));
//             }
//         })
//         .catch(error => {
//             console.error("Error fetching trip details:", error);
//             alert("Failed to load trip details.");
//         });
// }


// Update Trip Function
$("#updateButton").on("click", function () {
    let daysContainer = document.getElementById("daysContainer");
    let dayForms = daysContainer.getElementsByClassName("update-day-form");
    let errorMessages = [];
    let formData = new FormData();

   
    let pdfType = document.querySelector("input[name='pdfType']:checked").value;

    for (let i = 0; i < dayForms.length; i++) {
        let dayForm = dayForms[i];
        let stay = dayForm.querySelector("input[name='stay[]']").value.trim();
        let date = dayForm.querySelector("input[name='date[]']").value.trim();
         let itineraryTextarea = dayForm.querySelector("textarea[name='itinerary[]']");
         let itineraryId = itineraryTextarea.id;
     
         // Use CKEditor content if available, fallback to textarea value
         let itinerary = (typeof CKEDITOR !== "undefined" && CKEDITOR.instances[itineraryId])
             ? CKEDITOR.instances[itineraryId].getData().trim()
             : itineraryTextarea.value.trim();

        let vsImageInput = dayForm.querySelector("input[name='images[]']");
        let vsImages = vsImageInput.files.length > 0 ? vsImageInput.files[0] : null;

        if (!stay || !date || !itinerary) {
            errorMessages.push(`Please fill all fields for Day ${i + 1}`);
        } else {
            formData.append(`days[${i}][stay]`, stay);
            formData.append(`days[${i}][date]`, date);
            formData.append(`days[${i}][itinerary]`, itinerary);
            if (vsImages) {
                formData.append(`days[${i}][vsImages]`, vsImages);
            }
        }
    }

    let id = document.getElementById("id").value.trim();
    let tripId = document.getElementById("tripId").value.trim();
    let userName = document.getElementById("userName").value.trim();
    let tourName = document.getElementById("tourName").value.trim();
    let checkIn = document.getElementById("checkIn").value.trim();
    let checkOut = document.getElementById("checkOut").value.trim();
    let numAdults = document.getElementById("numAdults").value.trim();
    let numChildren = document.getElementById("numChildren").value.trim();
    let inclusion = inclusionvalue.getData();
    let exclusion = exclusionvalue.getData();
    let notes = notevalue.getData();
    let cost = costvalue.getData();

    let hotel = hotelvalue.getData();
    let flight = flightvalue.getData();
    let tourImages = document.getElementById("timages").files[0] || null;
    let flightImages = document.getElementById("flightimages").files[0] || null;

    if (tourImages) formData.append("tourImages", tourImages);
    if (flightImages) formData.append("flightimages", flightImages);

    if (!tripId || !tourName || !checkIn || !checkOut || !numAdults || !numChildren) {
        errorMessages.push("Please fill in all required trip details.");
    }

    if (errorMessages.length > 0) {
        button.innerHTML = `Save`;
        alert(errorMessages.join("\n"));
        return;
    }

    // Append general form data
    formData.append("tripId", tripId);
    formData.append("userName", userName);
    formData.append("tourName", tourName);
    formData.append("checkIn", checkIn);
    formData.append("checkOut", checkOut);
    formData.append("numAdults", numAdults);
    formData.append("numChildren", numChildren);
    formData.append("inclusion", inclusion);
    formData.append("exclusion", exclusion);
    formData.append("notes", notes);
    formData.append("cost", cost);
    formData.append("hotel", hotel);
    formData.append("flight", flight);

    fetch(`/itinerary/${id}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (pdfType === 'Single Pdf') {
                window.open(`/singlepdf-itinerary/${id}`, "_blank");
            } else if (pdfType === 'Multiple Pdf') {
                window.open(`/multiplepdf-itinerary/${id}`, "_blank");
            } else {
                alert("Data updated successfully!");
            }

            setTimeout(() => {
                window.location.href = "/adminitinerary";
            }, 1000);
        } else {
            alert("Error updating itinerary: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error updating trip:", error);
        alert("An unexpected error occurred.");
    });
});

// Add Day Function - 
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









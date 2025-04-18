var hotelvalue, flightvalue, inclusionvalue, exclusionvalue, notevalue, costvalue;

document.addEventListener("DOMContentLoaded", function () {
    // Function to initialize CKEditor for each field
    function initializeEditor(selector, callback) {
        const element = document.querySelector(selector);
        if (element) {
            ClassicEditor.create(element)
                .then(editor => {
                    callback(editor);
                    editor.model.document.on('change:data', () => {
                        console.log(`${selector} content changed:`, editor.getData());
                    });
                    console.log(`${selector} editor initialized`);
                })
                .catch(error => console.error(`Error initializing ${selector}:`, error));
        } else {
            console.error(`Element ${selector} not found in the DOM.`);
        }
    }

    initializeEditor('#hotel', editor => hotelvalue = editor);
    initializeEditor('#flight', editor => flightvalue = editor);
    initializeEditor('#inclusion', editor => inclusionvalue = editor);
    initializeEditor('#exclusion', editor => exclusionvalue = editor);
    initializeEditor('#notes', editor => notevalue = editor);
    initializeEditor('#cost', editor => costvalue = editor);

    // Handle the Submit Button Click
    const submitButton = document.getElementById("submitButton");
    if (submitButton) {
        submitButton.addEventListener("click", function () {
            handleSubmit(submitButton);
        });
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

// Helper function to adjust adult/children count
function adjustCount(selector, delta, min) {
    const input = document.querySelector(selector);
    let value = parseInt(input.value) || 0;
    input.value = Math.max(min, value + delta);
}

// Submit Function
// function handleSubmit(button) {
//     button.innerHTML = `
//         <div class="spinner-border text-light" role="status">
//             <span class="visually-hidden">Loading...</span>
//         </div>
//     `;

//     const dayForms = document.querySelectorAll("#daysContainer .day-form");
//     const formData = new FormData();
//     const errorMessages = [];

//     const pdfType = document.querySelector("input[name='pdfType']:checked").value;

//     dayForms.forEach((day, i) => {
//         const stay = day.querySelector("input[name='stay[]']").value.trim();
//         const date = day.querySelector("input[name='date[]']").value.trim();
//         const itinerary = day.querySelector("textarea[name='itinerary[]']").value.trim();
//         const vsImage = day.querySelector("input[name='images[]']").files[0] || null;

//         if (!stay || !date || !itinerary) {
//             // errorMessages.push(`Please fill all fields for Day ${i + 1}`);
//         } else {
//             formData.append(`days[${i}][stay]`, stay);
//             formData.append(`days[${i}][date]`, date);
//             formData.append(`days[${i}][itinerary]`, itinerary);
//             if (vsImage) {
//                 formData.append(`days[${i}][vsImages]`, vsImage);
//             }
//         }
//     });

//     // Validate basic trip fields
//     const fields = ["tripId", "userName", "tourName", "checkIn", "checkOut", "numAdults", "numChildren"];
//     for (let field of fields) {
//         const input = document.getElementById(field);
//         if (!input || !input.value.trim()) {
//             // errorMessages.push(`Please fill in all required trip details.`);
//             break;
//         }
//         formData.append(field, input.value.trim());
//     }

//     const tourImage = document.getElementById("timages").files[0];
//     const flightImage = document.getElementById("flightimages").files[0];
//     if (tourImage) formData.append("tourImages", tourImage);
//     if (flightImage) formData.append("flightimages", flightImage);

//     if (errorMessages.length) {
//         alert(errorMessages.join("\n"));
//         button.textContent = "Save";
//         return;
//     }

//     // Append editor content
//     formData.append("inclusion", inclusionvalue.getData());
//     formData.append("exclusion", exclusionvalue.getData());
//     formData.append("notes", notevalue.getData());
//     formData.append("cost", costvalue.getData());
//     formData.append("hotel", hotelvalue.getData());
//     formData.append("flight", flightvalue.getData());

//     // Submit the form using fetch API
//     fetch("/itinerary/store", {
//         method: "POST",
//         body: formData
//     })
//         .then(res => res.text())
//         .then(data => {
//             if (data) {
//                 const redirectUrl = pdfType === 'Single Pdf'
//                     ? `singlepdf.php?id=${data}`
//                     : `generatepdf.php?id=${data}`;
//                 window.location.href = redirectUrl;
//             } else {
//                 alert("Error processing request. Please try again.");
//             }
//             button.textContent = "Save";
//         })
//         .catch(error => {
//             console.error("Submission error:", error);
//             alert("An error occurred while submitting the form.");
//             button.textContent = "Save";
//         });
// }
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
    const fields = ["userName", "tourName", "checkIn", "checkOut", "numAdults", "numChildren", "officerName"];
    for (let field of fields) {
        const input = document.getElementById(field);
        if (!input || !input.value.trim()) {
            errorMessages.push(`Please fill in the ${field}.`);
            break;
        }
        formData.append(field, input.value.trim());
    }

    const tripIdInput = document.getElementById("tripId");
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
        button.textContent = "Save";
        return;
    }

    fetch("/itinerary/store", {
        method: "POST",
        body: formData,
    })
        .then(res => res.json())
        .then(data => {
            if (data && data.id) {
                const redirectUrl = pdfType === 'Single Pdf'
                redirectUrl = `/download-itinerary/${tourId}?id=${data.id}`;
                //     ? `/singlepdf-itinerary/{id}=${data.id}`
                    
                //     : `/generatepdf.php?id=${data.id}`;
                // window.location.href = redirectUrl;
            } else {
                alert("Error processing request.");
                button.textContent = "Save";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while submitting.");
            button.textContent = "Save";
        });
}


document.addEventListener("DOMContentLoaded", function () {
    // Event listener for check-in and check-out date changes
    const checkInInput = document.getElementById("checkIn");
    const checkOutInput = document.getElementById("checkOut");

    checkInInput.addEventListener("change", calculateDays);
    checkOutInput.addEventListener("change", calculateDays);

    // Function to calculate days between check-in and check-out
    function calculateDays() {
        let checkIn = document.getElementById("checkIn").value;
        let checkOut = document.getElementById("checkOut").value;
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

    // Initialize the day calculation on page load
    calculateDays();
});


// Add Day Function (for existing data)
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

    // Initialize CKEditor (if using it)
    if (typeof CKEDITOR !== "undefined") {
        CKEDITOR.replace(uniqueId);
    }
}

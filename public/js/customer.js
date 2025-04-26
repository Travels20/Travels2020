
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

$(document).ready(function () {

    
    document.addEventListener("DOMContentLoaded", function () {
        function toggleRelationshipInput() {
            console.log("toggleRelationshipInput function triggered.");
        }

        let selectElement = document.getElementById("relationshipSelect");
        if (selectElement) {
            selectElement.addEventListener("change", toggleRelationshipInput);
        }
    });

    function updatePassengerAccordion() {
        let numAdults = parseInt($('#numAdults').val()) || 0;
        let numChildren = parseInt($('#numChildren').val()) || 0;
        let passengerAccordion = $('#passengerAccordion');

        passengerAccordion.empty(); // Clear existing entries

        let adultCount = 1;
        let childCount = 1;

        for (let i = 1; i <= numAdults + numChildren; i++) {
            let passengerType = i <= numAdults ? 'Adult' : 'Child';
            let passengerIndex = i <= numAdults ? adultCount++ : childCount++;

            let passengerHtml = `
                <div class="accordion-item">
                  <form class="passenger-details">
                    <h2 class="accordion-header" id="heading${i}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${i}" aria-expanded="false" aria-controls="collapse${i}">
                            ${passengerType} ${passengerIndex} Details
                        </button>
                    </h2>
                    <div id="collapse${i}" class="accordion-collapse collapse" aria-labelledby="heading${i}" data-bs-parent="#passengerAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <input type="hidden" name="passengerType" value="${passengerType} ${passengerIndex}">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GivenName</label>
                                    <input type="text" class="form-control required" name="passengerFirstName" placeholder="Enter FirstName">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">LastName</label>
                                    <input type="text" class="form-control required" name="passengerLastName" placeholder="Enter LastName">
                                </div>
                             
                                 <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control required mobileNumber" name="mobileNumber" 
                                        placeholder="Enter Mobile Number" maxlength="10" pattern="[0-9]{10}">
                                    <small class="error-message text-danger d-none">Please enter a valid 10-digit mobile number.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control required" name="email" placeholder="Enter email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <div class="d-flex">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="gender" value="Male" id="genderMale" required>
                                            <label class="form-check-label" for="genderMale">Male</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" value="Female" id="genderFemale">
                                            <label class="form-check-label" for="genderFemale">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control required" name="dob">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Anniversary</label>
                                    <input type="date" class="form-control required" name="anniversary">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" name="panNumber" placeholder="Enter PAN Number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Number</label>
                                    <input type="text" class="form-control required" name="passportNumber" placeholder="Enter Passport Number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Issue City</label>
                                    <input type="text" class="form-control" name="passportIssueCity" placeholder="Enter Passport Issue City">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Issue Country</label>
                                    <select class="form-select" id="passportIssueCountry" name="passportIssueCountry">
                                        <option value="" selected disabled>Select Country</option>
                                        <option value="India">India</option>
                                        <option value="United States">United States</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Issue Date</label>
                                    <input type="date" class="form-control" name="passportIssueDate">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Expiry Date</label>
                                    <input type="date" class="form-control" name="passportExpiryDate">
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Front</label>
                                    <input type="file" class="form-control" name="passportFront" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Passport Back</label>
                                    <input type="file" class="form-control" name="passportBack" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pan Card</label>
                                    <input type="file" class="form-control" name="panCard" >
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>`;
            passengerAccordion.append(passengerHtml);
        }
    }

    updatePassengerAccordion();

    $('#numAdults, #numChildren').on('input', updatePassengerAccordion);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // $("#confirmSubmit").click(function (event) {
    //     event.preventDefault();
    
    //     const form = $("#customerForm")[0];
    //     const formData = new FormData(form);
    //     const passengers = $(".passenger-details");
    
    //     passengers.each(function (index) {
    //         $(this).find("input, select").each(function () {
    //             const fieldName = $(this).attr("name");
    //             if (!fieldName) return;
    
    //             const cleanName = fieldName.replace(/\[\]$/, "");
    //             const inputType = $(this).attr("type");
    
    //             if (inputType === "radio") {
    //                 if ($(this).is(":checked")) {
    //                     formData.append(`passengers[${index}][${cleanName}]`, $(this).val());
    //                 }
    //             } else if (inputType === "file") {
    //                 const files = this.files;
    //                 if (files.length > 0) {
    //                     formData.append(`passengers[${index}][${cleanName}]`, files[0]);
    //                 }
    //             } else {
    //                 formData.append(`passengers[${index}][${cleanName}]`, $(this).val().trim());
    //             }
    //         });
    //     });
    
    //     // Explicitly set customer-level fields if needed
    //     const customerFields = ['travel_from', 'travel_to', 'destination', 'relationship', 'adults', 'children'];
    //     customerFields.forEach(field => {
    //         const value = $(`[name="${field}"]`).val();
    //         if (value !== undefined && value !== null) {
    //             formData.set(field, value.trim());
    //         }
    //     });
    
    //     const button = $("#confirmSubmit");
    //     button.prop("disabled", true).text("Saving...");
    
    //     fetch("/customers/store", {
    //         method: "POST",
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    //         },
    //         body: formData
    //     })
    //     .then(response => response.json())
    //     .then(response => {
    //         console.log("Server Response:", response);
    //         if (response.success) {
    //             alert("Form submitted successfully!");
    //             $("#customerForm")[0].reset();
    //         } else {
    //             alert(response.message || "Something went wrong.");
    //         }
    //     })
    //     .catch(error => {
    //         console.error("Error:", error);
    //         alert("An error occurred while submitting.");
    //     })
    //     .finally(() => {
    //         button.prop("disabled", false).text("Save");
    //     });
    // });
    

  
  // SUBMIT BUTTON HANDLER
  $("#submitButton, #confirmSubmit").click(function (event) {
      event.preventDefault();
      let isValid = true;
  
      $(".error-message").remove();
      $(".is-invalid").removeClass("is-invalid");
  
      $("#customerForm .required").each(function () {
          if ($(this).val().trim() === "") {
              isValid = false;
              $(this).addClass("is-invalid");
              $(this).after('<small class="error-message text-danger">This field is required.</small>');
          }
      });
  
      if (!isValid) return;
  
      let form = $("#customerForm")[0];
      let formData = new FormData(form);
      let passengers = $(".passenger-details");
  
      passengers.each(function (index) {
          $(this).find("input, select").each(function () {
              let fieldName = $(this).attr("name");
              if (!fieldName) return;
  
              let cleanName = fieldName.replace(/\[\]$/, "");
              let type = $(this).attr("type");
  
              let key = `passengers[${index}][${cleanName}]`;
  
              if (type === "radio") {
                  if ($(this).is(":checked")) {
                      formData.append(key, $(this).val());
                  }
              } else if (type === "file") {
                  if (this.files.length > 0) {
                      formData.append(key, this.files[0]);
                  }
              } else {
                  formData.append(key, $(this).val().trim());
              }
          });
      });
  
      $.ajax({
          url: "/customers/store",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          dataType: "json",
          beforeSend: function () {
              $("#submitButton, #confirmSubmit").prop("disabled", true).text("Saving...");
          },
          success: function (response) {
              console.log("Server Response:", response);
              if (response.success) {
                  alert("Form submitted successfully!");
                  $("#customerForm")[0].reset();
              } else {
                  alert("Error: " + response.message);
              }
          },
          error: function (xhr, status, error) {
              alert("Something went wrong while submitting. Please check console.");
          },
          complete: function () {
              $("#submitButton, #confirmSubmit").prop("disabled", false).text("Save");
          }
      });
  });
  

// REVIEW BUTTON HANDLER
document.getElementById("reviewButton").addEventListener("click", function () {
    let travelFrom = document.getElementById("travelFrom").value;
    let travelTo = document.getElementById("travelTo").value;
    let destination = document.getElementById("destinationSelect").value;
    let destinationInput = document.getElementById("destinationinput").value;
    if (destination === "Others") {
        destination = destinationInput;
    }
    let relationship = document.getElementById("relationshipSelect").value;
    let numAdults = document.getElementById("numAdults").value;
    let numChildren = document.getElementById("numChildren").value;

    if (!travelFrom || !travelTo || !destination || !relationship) {
        alert("Please fill in all required fields before reviewing.");
        return;
    }

    let reviewDetails = `
        <table class="table">
            <tbody>
                <tr><td><strong>Travel Dates:</strong></td><td>${travelFrom} to ${travelTo}</td></tr>
                <tr><td><strong>Destination:</strong></td><td>${destination}</td></tr>
                <tr><td><strong>Travelling Type:</strong></td><td>${relationship}</td></tr>
                <tr><td><strong>Travellers:</strong></td><td>Adults: ${numAdults}, Children: ${numChildren}</td></tr>
            </tbody>
        </table>
    `;

    let passengerDetails = "";
    let passengers = document.querySelectorAll(".passenger-details");

    passengers.forEach((passenger, index) => {
        let firstName = passenger.querySelector("input[name='passengerFirstName']")?.value || "N/A";
        let lastName = passenger.querySelector("input[name='passengerLastName']")?.value || "N/A";
        let mobileNumber = passenger.querySelector("input[name='mobileNumber']")?.value || "N/A";
        let email = passenger.querySelector("input[name='email']")?.value || "N/A";
        let gender = passenger.querySelector("input[name='gender']:checked")?.value || "N/A";
        let dob = passenger.querySelector("input[name='dob']")?.value || "N/A";
        let anniversary = passenger.querySelector("input[name='anniversary']")?.value || "N/A";
        let passportNumber = passenger.querySelector("input[name='passportNumber']")?.value || "N/A";
        let panNumber = passenger.querySelector("input[name='panNumber']")?.value || "N/A";
        let passportIssueCity = passenger.querySelector("input[name='passportIssueCity']")?.value || "N/A";
        let passportIssueDate = passenger.querySelector("input[name='passportIssueDate']")?.value || "N/A";
        let passportExpiryDate = passenger.querySelector("input[name='passportExpiryDate']")?.value || "N/A";

        let passportFront = passenger.querySelector("input[name='passportFront']")?.files[0];
        let passportBack = passenger.querySelector("input[name='passportBack']")?.files[0];
        let panCard = passenger.querySelector("input[name='panCard']")?.files[0];

        let passportFrontURL = passportFront ? URL.createObjectURL(passportFront) : '';
        let passportBackURL = passportBack ? URL.createObjectURL(passportBack) : '';
        let panCardURL = panCard ? URL.createObjectURL(panCard) : '';

        passengerDetails += `
            <table class="table">
                <tbody>
                    <tr><td><strong>Passenger ${index + 1}:</strong></td><td>${firstName} ${lastName}</td></tr>
                    <tr><td><strong>Gender:</strong></td><td>${gender}</td></tr>
                    <tr><td><strong>DOB:</strong></td><td>${dob}</td></tr>
                    <tr><td><strong>Anniversary:</strong></td><td>${anniversary}</td></tr>
                    <tr><td><strong>Mobile:</strong></td><td>${mobileNumber}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${email}</td></tr>
                    <tr><td><strong>Passport Number:</strong></td><td>${passportNumber}</td></tr>
                    <tr><td><strong>PAN Number:</strong></td><td>${panNumber}</td></tr>
                    <tr><td><strong>Passport Issue City:</strong></td><td>${passportIssueCity}</td></tr>
                    <tr><td><strong>Passport Issue Date:</strong></td><td>${passportIssueDate}</td></tr>
                    <tr><td><strong>Passport Expiry Date:</strong></td><td>${passportExpiryDate}</td></tr>
                    <tr><td><strong>Passport Front:</strong></td><td>${passportFrontURL ? `<img src="${passportFrontURL}" style="width: 50px; height: 50px;">` : "N/A"}</td></tr>
                    <tr><td><strong>Passport Back:</strong></td><td>${passportBackURL ? `<img src="${passportBackURL}" style="width: 50px; height: 50px;">` : "N/A"}</td></tr>
                    <tr><td><strong>Pan Card:</strong></td><td>${panCardURL ? `<img src="${panCardURL}" style="width: 50px; height: 50px;">` : "N/A"}</td></tr>
                </tbody>
            </table>
        `;
    });

    document.getElementById("reviewDetails").innerHTML = reviewDetails + passengerDetails;

    let reviewModal = new bootstrap.Modal(document.getElementById("reviewModal"));
    reviewModal.show();
});



    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.get('id');
    // alert(myParam
    if (myParam) {
        editcustomer(myParam);
        $('#confirmSubmit').hide();
        $('#reviewButton').hide();
        // $('#imagehide').show();
        // $('#imagehideflight').show();

    } else {
        $('#confirmSubmit').show();
        $('#updateButton').hide();
        // $('#imagehide').hide();
        // $('#imagehideflight').hide();
    }


    function editcustomer(Id) {
        fetch(`controller/getcustomerDetails.php?id=${Id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not OK");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    let customer = data.customer || {};
                    let passengers = data.passengers || [];

                    // Populate main customer details
                    $("#travelFrom").val(customer.travel_from || "");
                    $("#travelTo").val(customer.travel_to || "");
                    $("#destinationSelect").val(customer.destination || "");
                    $("#relationshipSelect").val(customer.relationship || "");
                    $("#numAdults").val(customer.adults || "");
                    $("#numChildren").val(customer.children || "");

                    if (customer.destination === "Others") {
                        $("#destination").removeClass("d-none").val(customer.customDestination || "");
                    } else {
                        $("#destination").addClass("d-none").val("");
                    }

                    if (customer.relationship === "Others") {
                        $("#relationship").removeClass("d-none").val(customer.customRelationship || "");
                    } else {
                        $("#relationship").addClass("d-none").val("");
                    }

                    // Update passenger accordion to match the number of passengers
                    updatePassengerAccordion();

                    console.log("Passengers Array:", passengers);

                    if (!Array.isArray(passengers) || passengers.length === 0) {
                        console.error("No passengers found.");
                        return;
                    }

                    $(".passenger-details").each((index, element) => {
                        let passengerForm = $(element);
                        let passengerType = passengerForm.find("input[name='passengerType']").val().trim();

                        let passenger = passengers.find(p => p.passengers.trim() === passengerType);
                        if (!passenger) return;

                        let fieldMap = {
                            passengerFirstName: "first_name",
                            passengerLastName: "last_name",
                            mobileNumber: "mobile_number",
                            email: "email",
                            dob: "dob",
                            anniversary: "anniversary",
                            panNumber: "pan_number",
                            passportNumber: "passport_number",
                            passportIssueCity: "passport_issue_city",
                            passportIssueCountry: "passport_issue_country",
                            passportIssueDate: "passport_issue_date",
                            passportExpiryDate: "passport_expiry_date"
                        };

                        passengerForm.find("input, select").each(function () {
                            let fieldName = $(this).attr("name");
                            let jsonField = fieldMap[fieldName];
                            if (jsonField && passenger[jsonField] !== undefined) {
                                $(this).val(passenger[jsonField]);
                            }
                            if (fieldName === "gender" && $(this).val() === passenger.gender) {
                                $(this).prop("checked", true);
                            }
                        });

                        // Display uploaded images
                        if (passenger.passport_front) {
                            passengerForm.find("[name='passportFront']").after(
                                `<img src="${passenger.passport_front}" class="img-thumbnail mt-2" width="100">`
                            );
                        }
                        if (passenger.passport_back) {
                            passengerForm.find("[name='passportBack']").after(
                                `<img src="${passenger.passport_back}" class="img-thumbnail mt-2" width="100">`
                            );
                        }
                        if (passenger.pan_card) {
                            passengerForm.find("[name='panCard']").after(
                                `<img src="${passenger.pan_card}" class="img-thumbnail mt-2" width="100">`
                            );
                        }
                    });
                } else {
                    console.error("Error: ", data.message || "Failed to fetch customer details.");
                }
            })
            .catch(error => {
                console.error("Error fetching customer details:", error);
            });
    }



    document.getElementById("updateButton").addEventListener("click", function () {
        const formElement = document.getElementById("customerForm");
        const formData = new FormData(formElement);

        // Collect passenger details
        let passengers = [];
        document.querySelectorAll(".passenger-details").forEach((passengerForm, index) => {
            let passengerData = {
                passengerType: passengerForm.querySelector("input[name='passengerType']").value,
                first_name: passengerForm.querySelector("input[name='passengerFirstName']").value,
                last_name: passengerForm.querySelector("input[name='passengerLastName']").value,
                mobile_number: passengerForm.querySelector("input[name='mobileNumber']").value,
                email: passengerForm.querySelector("input[name='email']").value,
                gender: passengerForm.querySelector("input[name='gender']:checked")?.value || "",
                dob: passengerForm.querySelector("input[name='dob']").value,
                anniversary: passengerForm.querySelector("input[name='anniversary']").value,
                pan_number: passengerForm.querySelector("input[name='panNumber']").value,
                passport_number: passengerForm.querySelector("input[name='passportNumber']").value,
                passport_issue_city: passengerForm.querySelector("input[name='passportIssueCity']").value,
                passport_issue_country: passengerForm.querySelector("select[name='passportIssueCountry']").value,
                passport_issue_date: passengerForm.querySelector("input[name='passportIssueDate']").value,
                passport_expiry_date: passengerForm.querySelector("input[name='passportExpiryDate']").value
            };

            passengers.push(passengerData);

            // Append file inputs separately
            let passportFront = passengerForm.querySelector("input[name='passportFront']").files[0];
            let passportBack = passengerForm.querySelector("input[name='passportBack']").files[0];
            let panCard = passengerForm.querySelector("input[name='panCard']").files[0];

            if (passportFront) formData.append(`passportFront_${passengerId}`, passportFront);
            if (passportBack) formData.append(`passportBack_${passengerId}`, passportBack);
            if (panCard) formData.append(`panCard_${passengerId}`, panCard);
        });

        // Convert passenger array to JSON and add to formData
        formData.append("passengers", JSON.stringify(passengers));

        fetch(`controller/updateController.php?id=${myParam}`, {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Data updated successfully!");
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error updating trip:", error);
            });
    });



});


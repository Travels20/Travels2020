function toggleDestination(selectElement) {
    const destinationInput = document.getElementById("destinationOthers");

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
  // Submit form and generate invoice
$('#InvoiceBtn').click(function (e) {
    e.preventDefault();

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

    let form = document.getElementById('invoiceForm');
    let formData = new FormData(form);
    let button = this;

    // Disable button and show loading
    $(button).prop('disabled', true).html("Saving...");

    fetch("/invoices/store", {
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
            const invoiceId = data.id;
            let redirectUrl = `/invoicepdf/${invoiceId}`;
            window.open(redirectUrl, '_blank');
        } else {
            alert("Error processing request.");
        }

        $(button).prop('disabled', false).html("Save");
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while submitting.");
        $(button).prop('disabled', false).html("Save");
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("invoiceTableBody");
    const table = document.querySelector("#invoiceTable");

    // ✅ Check if the table body exists
    if (!tableBody || !table) {
        console.error("Table or table body element not found in the DOM.");
        return;
    }

    fetch("/listinvoices") // Make sure this route returns JSON properly
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch itinerary data.");
            }
            return response.json();
        })
        .then(invoices => {
            tableBody.innerHTML = ""; // Clear previous content

            invoices.forEach((invoice, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                <td>${index + 1}</td>
                <td>${invoice.customer_name || ''}</td>
                <td>${invoice.destination || ''}</td>
                <td>${invoice.travel_from || ''}</td>
                <td>${invoice.travel_to || ''}</td>
                <td>${invoice.num_adults || 0}</td>
                <td>${invoice.num_children || 0}</td>
                <td>
                    <a href="/invoicepdf/${invoice.id}" target="_blank" title="View PDF">
                        <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="deleteInvoice(${invoice.id})">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;

                tableBody.appendChild(row);
            });

            // ✅ Initialize DataTable
            new DataTable('#invoiceTable');
        })
        .catch(error => {
            // console.error("Error fetching itinerary data:", error);
        });
});


});

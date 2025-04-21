document.addEventListener("DOMContentLoaded", function () {
    fetch("{{ route('invoices.list') }}")
        .then(response => response.json())
        .then(invoices => {
            const tableBody = document.getElementById("invoiceTableBody");
            const table = $('#invoicelistTable');

            // Destroy existing DataTable instance if any
            if ($.fn.DataTable.isDataTable('#invoicelistTable')) {
                table.DataTable().clear().destroy();
            }

            tableBody.innerHTML = ""; // Clear previous content

            invoices.forEach((invoice, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td> 
                    <td>${invoice.invoice_no || ''}</td> 
                    <td>${invoice.username || ''}</td>
                    <td>${invoice.tour_name || ''}</td>
                    <td>${invoice.check_in || ''}</td>
                    <td>${invoice.check_out || ''}</td>
                    <td>${invoice.adults || 0}</td>
                    <td>${invoice.children || 0}</td>
                    <td>
                        <a href="/invoices/edit/${invoice.id}" title="View">
                            <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="deleteInvoice(${invoice.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Re-initialize DataTable
            table.DataTable();
        })
        .catch(error => {
            console.error("Error fetching invoice data:", error);
        });
});

// Toggle destination input visibility
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
                    let redirectUrl = `/invoicepdf/${response.invoice_id}`;
                    window.open(redirectUrl, '_blank');
                } else {
                    alert('Unexpected error.');
                }
            },
            error: function (xhr) {
                console.error(xhr);
                alert('Error: could not save');
            }
        });
    });
});

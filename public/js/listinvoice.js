$(document).ready(function () {
    $('#InvoiceBtn').click(function (e) {
        e.preventDefault();  // Prevent default form submission

        let isValid = true;

        // Validate required fields
        $('#invoiceForm [required]').each(function () {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');  // Mark the field as invalid
            } else {
                $(this).removeClass('is-invalid');  // Remove invalid class if the field is filled
            }
        });

        if (!isValid) {
            alert('Please fill all required fields.');
            return;
        }

        // Serialize form data for AJAX submission
        let formData = $('#invoiceForm').serialize();

        // Make an AJAX POST request to store the invoice
        $.ajax({
            url: "{{ route('invoices.store') }}", // The route to handle POST requests
            method: "POST",  // Ensure this is a POST request
            data: formData,  // Sending data in the body of the request
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Adding CSRF token
            },
            success: function (response) {
                console.log("Invoice saved:", response);

                if (response.status === 'success') {
                    alert('Invoice saved!');
                    let redirectUrl = `/invoicepdf/${data.invoice_id}`;  // Redirect to PDF generation route
                    window.open(redirectUrl, '_blank'); 
                    // fetchInvoices();
                } else {
                    alert('Unexpected error.');
                }
            },
            error: function (xhr) {
                console.error(xhr);
                alert('Error: could not save invoice');
            }
        });
    });

    // Fetch all invoices and display them
    // function fetchInvoices() {
    //     $.ajax({
    //         url: "{{ route('invoices.list') }}",
    //         method: "GET",
    //         success: function (response) {
    //             console.log('Invoices fetched:', response);
    //             let tableBody = $('#invoiceTableBody');
    //             tableBody.empty(); // Clear the table before appending new data

    //             response.forEach((invoice, index) => {
    //                 tableBody.append(`
    //                     <tr>
    //                         <td>${index + 1}</td>
    //                         <td>${invoice.invoice_no}</td>
    //                         <td>${invoice.customer_name}</td>
    //                         <td>${invoice.destination}</td>
    //                         <td>${invoice.travel_from}</td>
    //                         <td>${invoice.travel_to}</td>
    //                         <td>${invoice.num_adults}</td>
    //                         <td>${invoice.num_children}</td>
    //                         <td>
    //                             <a href="/invoices/edit/${invoice.id}" title="Edit">
    //                                 <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
    //                             </a>
    //                             <button class="btn btn-danger btn-sm" onclick="deleteInvoice(${invoice.id})">
    //                                 <i class="fas fa-trash-alt"></i>
    //                             </button>
    //                         </td>
    //                     </tr>
    //                 `);
    //             });
    //         },
    //         error: function (xhr) {
    //             console.error(xhr);
    //             alert('Error: could not fetch invoices');
    //         }
    //     });
    // }

    document.addEventListener("DOMContentLoaded", function () {
        fetch("{{ route('listinvoices') }}")
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
                alert('Error fetching invoice data.');
            });
    });


});

document.addEventListener("DOMContentLoaded", function () {
    fetch("{{ route('invoices.list') }}") // Fetch invoices using AJAX
        .then(response => response.json())
        .then(invoices => {
            let tableBody = document.getElementById("invoiceTableBody");
            let table = $('#invoicelistTable'); // Select the table

            tableBody.innerHTML = ""; // Clear previous content

            invoices.forEach((invoice, index) => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td> 
                    <td>${invoice.invoice_no}</td> 
                    <td>${invoice.username}</td>
                    <td>${invoice.tour_name}</td>
                    <td>${invoice.check_in}</td>
                    <td>${invoice.check_out}</td>
                    <td>${invoice.adults}</td>
                    <td>${invoice.children}</td>
                    <td>
                        <a href='/invoices/edit/${invoice.id}' title="view">
                            <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="deleteInvoice(${invoice.id})"><i class="fas fa-trash-alt"></i></button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTable after content is added
            table.DataTable();
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
});

// Delete function for invoices
function deleteInvoice(invoiceId) {
    if (confirm("Are you sure you want to delete this invoice?")) {
        fetch(`/invoices/${invoiceId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload(); // Reload page to reflect changes
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error deleting invoice:", error);
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("itineraryTableBody");
    const table = document.querySelector("#itinerarylistTable");

    // ✅ Check if the table body exists
    if (!tableBody || !table) {
        console.error("Table or table body element not found in the DOM.");
        return;
    }

    fetch("/listitineray") // Make sure this route returns JSON properly
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch itinerary data.");
            }
            return response.json();
        })
        .then(trips => {
            tableBody.innerHTML = ""; // Clear previous content

            trips.forEach((trip, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td> 
                    <td>${trip.trip_id || ''}</td> 
                    <td>${trip.username || ''}</td>
                    <td>${trip.tour_name || ''}</td>
                    <td>${trip.check_in || ''}</td>
                    <td>${trip.check_out || ''}</td>
                    <td>${trip.adults || 0}</td>
                    <td>${trip.children || 0}</td>
                    <td>
                       <a href="itineraryform/${trip.id}" title="Edit">
                            <button class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                        </a>

                    </td>
                `;
                // <button class="btn btn-warning btn-sm" onclick="editTour(${trip.id})">

                tableBody.appendChild(row);
            });

            // ✅ Initialize DataTable
            new DataTable('#itinerarylistTable');
        })
        .catch(error => {
            // console.error("Error fetching itinerary data:", error);
        });
});



